/* global console, module, require */
module.exports = function(__grunt){
	//--show run time
	require('time-grunt')(__grunt);

	//--autoload grunt tasks package.json
	require('load-grunt-tasks')(__grunt);

	//--path configuration
	var __paths = {
		bundle: '.'
	};
	//---assets
	__paths.stylesSrc = __paths.bundle + '/styles';
	__paths.stylesBuildsSrc = __paths.stylesSrc + '/builds';
	//--web
	__paths.assetsTarget = __paths.bundle + '/public';
	__paths.stylesTarget = __paths.assetsTarget + '/styles';
	__paths.stylesDev = __paths.stylesTarget + '/dev';
	__paths.stylesProd = __paths.stylesTarget + '/prod';

	//--grunt config
	__grunt.initConfig({
		autoprefixer: {
			dev: {
				options: {
					browsers: ['last 2 versions', '> 0.5%', '> 0.5% in US']
				}
				,src: __paths.stylesDev + '/**/*.css'
			}
			,prod: {
				options: {
					browsers: ['last 2 versions', '> 0.5%', '> 0.5% in US']
					,remove: false
				}
				,src: __paths.stylesProd + '/**/*.css'
			}
		}
		,sass: {
			options: {
				unixNewlines: true
			}
			,dev: {
				files: [{
					cwd: __paths.stylesBuildsSrc
					,dest: __paths.stylesDev
					,expand: true
					,ext: '.css'
					,extDot: 'last'
					,src: ['**/*.s{a,c}ss']
				}]
				,options: {
					lineNumbers: true
					,style: 'nested'
				}
			}
			,prod: {
				files: [{
					cwd: __paths.stylesBuildsSrc
					,dest: __paths.stylesProd
					,expand: true
					,ext: '.css'
					,extDot: 'last'
					,src: ['**/*.s{a,c}ss']
				}]
				,options: {
					style: 'compressed'
				}
			}
		}
		,watch: {
			css: {
				files: [{
					extDot: 'last'
					,src: [__paths.stylesSrc + '/**/*.s{a,c}ss']
				}]
				,tasks: ['build:css:dev']
			}
			,prod: {
				files: [{
					extDot: 'last'
					,src: [
						__paths.stylesSrc
					]
				}]
				,tasks: [
					'build:css:prod'
				]
			}
		}
	});

	//--tasks
	/*---
	convenient configuration map of all tasks to register
	key is name of task
	value is either a map or an array of tasks or a function to run
		Map: can have two keys, 'value' and 'description'.  'description' is optional description of task.  'value' is either an array of tasks or a function to run
		Something Else: will be used as the 'value' equivalent of the map
	*/
	var __tasks = {
		'default': function(){
			console.log('available commands:\n', Object.keys(__tasks).toString().replace(/,/g, ', '));
		}
		,'build:css': [
			'build:css:dev'
		]
		,'build:css:dev': [
			'sass:dev'
			,'autoprefixer:dev'
		]
		,'build:css:prod': [
			'sass:prod'
			,'autoprefixer:prod'
		]
		,'build:dev': [
			'build:css:dev'
		]
		,'build:prod': [
			'build:css:prod'
		]
		,'watch:dev': [
			'watch:css'
		]
	};
	//---loop through all tasks, registering each
	var _item;
	for(var _key in __tasks){
		if(__tasks.hasOwnProperty(_key)){
			//--items are objects or are converted to them
			_item = __tasks[_key];
			if(_item instanceof Array || typeof _item === 'function'){
				_item = {
					value: _item
				};
			}
			if(_item.description){
				__grunt.registerTask(_key, _item.description, _item.value);
			}else{
				__grunt.registerTask(_key, _item.value);
			}
		}
	}
};
