module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch : {
			html : {
				files : ['index.php'],
				options : {
					livereload : true
				}
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-git');
	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-composer');

	// Set up tasks with registerTask(<task-name>,<list-of-tasks-from-initConfig>)
	grunt.registerTask('start', ['connect','watch']);
};