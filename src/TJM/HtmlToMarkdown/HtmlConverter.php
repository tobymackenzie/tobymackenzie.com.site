<?php
namespace TJM\HtmlToMarkdown;
use League\HTMLToMarkdown\HtmlConverter as Base;

class HtmlConverter extends Base{
	public function convertInMarkdown($markdown){
		$explodedContent = explode("\n", $markdown);
		$content
			= $multilineHtmlContent
			= $lineFinal
			= $lineTmp
			= $explodedContent[] //-# so we can use same logic for last line
			= ''
		;
		$inCodeFence
			= $isCommentOpened
			= false
		;
		foreach($explodedContent as $line){
			//--start / stop codefence
			if(preg_match('/^```/', $line)){
				$content .= "{$line}\n";
				$inCodeFence = !$inCodeFence;
			//--output codefence lines directly
			}elseif($inCodeFence || substr($line, 0, 4) === '    '){
				$content .= "{$line}\n";
			//--if full HTML line, stick in var to be converted all at once
			}elseif(
				!$inCodeFence
				&& (
					substr(trim($line), 0, 1) === '<'
					|| $isCommentOpened
				)
			){
				$openCommentPos = strrpos($line, '<!--');
				$closeCommentPos = strrpos($line, '-->');
				if($openCommentPos !== false && ($closeCommentPos === false || $closeCommentPos < $openCommentPos)){
					$isCommentOpened = true;
				}elseif($closeCommentPos !== false){
					$isCommentOpened = false;
				}
				$multilineHtmlContent .= "{$line}\n";
			}else{
				if($multilineHtmlContent){
					//--strip comments: opinionated
					$multilineHtmlContent = preg_replace('/<!--.*-->/s', '', $multilineHtmlContent);
					$content .= $this->convert($multilineHtmlContent) . "\n";
					$multilineHtmlContent = '';
				}
				if($line){
					$content .= $this->convertMarkdownLine($line) . "\n";
				}else{
					$content .= "\n";
				}
			}
		}
		return $content;
	}
	protected function convertMarkdownLine($line){
		$newLine = '';
		$inCodeFence = substr($line, 0, 1) === '`';
		$context = $this;
		$pregCallback = function($matches) use ($context){
			return $context->convert($matches[0]);
		};
		foreach(explode('`', $line) as $lineBit){
			if($inCodeFence){
				$newLine .= "`{$lineBit}`";
			}else{
				$tmp = preg_replace_callback('@<[\w\-:]+.*>.*</[\w\-:]+>@', $pregCallback, $lineBit);
				$tmp = preg_replace_callback('@<[\w\-:]+.*/>@', $pregCallback, $tmp);
				$newLine .= $tmp;
			}
			$inCodeFence = !$inCodeFence;
		}
		return $newLine;
	}
}
