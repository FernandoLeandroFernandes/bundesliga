from fabric.api import *
env.hosts = ['']
env.use_ssh_config = True

def build():
	local('mkdir environment')
	local('mkdir environment/projects/bundesliga')
	local('mkdir environment/homestead')
	with lcd('environment/projects')
		run('git clone git@github.com:FernandoLeandroFernandes/bundesliga.git')
		run('composer install')