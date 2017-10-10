#!/usr/bin/env bash

# export BUNDESLIGA_HOME=$PWD/environment;

echo "Creating folders...";
mkdir -p environment/code/bundesliga environment/homestead;

echo "Cloning Laravel Homesteads repository from Github...";
git clone git@github.com:laravel/homestead.git environment/homestead;
cd environment/homestead;
chmod +x init.sh;
bash ./init.sh;

echo "Cloning Bundesliga repository from Github...";
git clone git@github.com:FernandoLeandroFernandes/bundesliga.git environment/code/bundesliga;

cp -f ../code/Vagrantfile .

bash vagrant -v;
if [ $? -ne 0 ]; then
	echo "Installing Vagrant 2.0...";
	wget -O vagrant_2.0.0_x86_64.deb https://releases.hashicorp.com/vagrant/2.0.0/vagrant_2.0.0_x86_64.deb;
	sudo dpkg -i vagrant_2.0.0_x86_64.deb;
	rm vagrant_2.0.0_x86_64.deb;
fi

bash vboxmanage --version;
if [ $? -ne 0 ]; then
	echo "Installing VirtualBox...";
	sudo apt-get install virtualbox;
fi

vagrant up

# chmod +x environment/code/bundesliga/fabfile.py;

#python fabfile