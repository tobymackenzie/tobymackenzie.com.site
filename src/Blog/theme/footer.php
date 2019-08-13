<?php
SymfonyHelper::$viewData['content'] = ob_get_contents();
ob_end_clean();
//-! what to do with sidebar?
// get_sidebar();
echo SymfonyHelper::getService('twig')->render('@Public/content.html.twig', SymfonyHelper::getViewData());
