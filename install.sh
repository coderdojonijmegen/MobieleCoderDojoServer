#!/usr/bin/env bash

if [[ $UID != 0 ]]; then
    echo "Please run this script with sudo:"
    echo "sudo $0 $*"
    exit 1
fi

update_os() {
	apt update &&
	apt dist-upgrade -y &&
	apt autoremove -y &&
	return 0
}

install_dependencies() {
	apt install -y apt-transport-https ca-certificates curl software-properties-common git hostapd iproute2 iw haveged dnsmasq iptables procps bash util-linux build-essential python3 python3-pip openssh-server apache2 php libapache2-mod-php &&
	curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add - &&
	sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable" &&
	apt update &&
	apt install -y docker-ce &&
	pip3 install docker-compose &&
	return 0
}

install_accesspoint() {
	git clone https://github.com/oblique/create_ap &&
	pushd create_ap &&
		make install &&
		cp ../install/create_ap.conf /etc/create_ap.conf &&
		systemctl enable create_ap &&
		systemctl start create_ap &&
		sleep 10 &&
		systemctl status create_ap &&
	popd &&
	rm -rf create_ap/ &&
	return 0
}

configure_firewall() {
	ufw --force reset &&
	ufw enable &&
	ufw allow in on ap0 to any port http &&
	ufw allow in on ap0 to any port ssh &&
	ufw allow in on eno1 to any port ssh &&
	ufw status verbose &&
	return 0
}

install_portainer() {
	pushd install/portainer &&
		docker-compose up -d &&
	popd &&
	return 0
}

install_cockpit() {
	apt install -y cockpit cockpit-docker cockpit-networkmanager &&
	cp install/cockpit.conf /etc/cockpit/cockpit.conf &&
	systemctl enable cockpit.socket &&
	return 0
}

install_wordpress() {
	pushd install/wordpress &&
		docker-compose up -d &&
	popd &&
	return 0
}

install_gitbucket() {
	pushd install/gitbucket &&
		docker-compose up -d &&
	popd &&
	return 0
}

configure_apache() {
	cp install/coderdojoserver.conf /etc/apache2/sites-available/coderdojoserver.conf &&
	a2dissite 000-default.conf &&
	a2ensite coderdojoserver.conf &&
	a2enmod proxy proxy_http proxy_wstunnel rewrite &&
	service apache2 reload &&
	return 0
}

update_os &&
install_dependencies &&
configure_firewall &&
install_accesspoint &&
install_cockpit &&
install_portainer &&
install_wordpress &&
install_gitbucket &&
configure_apache
ech "\n\n====================\nKlaar! Zie https://github.com/coderdojonijmegen/MobieleCoderDojoServer voor instructies om Portainer, WordPress en GitBucket te configureren.\n====================\n\n"
