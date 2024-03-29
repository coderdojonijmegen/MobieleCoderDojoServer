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
	apt install -y apt-transport-https ca-certificates curl software-properties-common git dnsmasq \
		bash build-essential python3 python3-pip openssh-server apache2 php libapache2-mod-php &&
	curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add - &&
	sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable" &&
	apt update &&
	apt install -y docker-ce &&
	pip3 install docker-compose &&
	mkdir -p /etc/systemd/system/docker.service.d/ &&
	cp install/docker-tcp-access.conf /etc/systemd/system/docker.service.d/override.conf &&
	systemctl restart docker.service &&
	return 0
}

install_accesspoint() {
	git clone https://github.com/oblique/create_ap &&
	cp install/create_ap/create_ap create_ap/create_ap
	pushd create_ap &&
		make install &&
		cp ../install/create_ap/create_ap.conf /etc/create_ap.conf &&
		systemctl enable create_ap &&
		systemctl start create_ap &&
		sleep 10 &&
		systemctl status create_ap &&
	popd &&
	rm -rf create_ap/ &&
	return 0
}

install_router() {
	apt install -y iproute2 iptables iw procps util-linux network-manager &&
	sed -i -e 's/#net\/ipv4\/ip_forward=1/net\/ipv4\/ip_forward=1/g' /etc/ufw/sysctl.conf &&
	rm /etc/netplan/*yaml && cp install/router/mcs.yaml /etc/netplan/mcs.yaml &&
	cp install/router/rc.local /etc/rc.local && chmod 755 /etc/rc.local &&
	cp install/router/hosts /etc/hosts &&
	cp install/router/dnsmasq.conf /etc/dnsmasq.conf &&
	return 0
}

configure_firewall_ap() {
	ufw --force reset &&
	ufw enable &&
	ufw allow in on ap0 to any port http &&
	ufw allow in on ap0 to any port ssh &&
	ufw allow in on ap0 to any port 53 &&
	ufw allow in on eno1 to any port ssh &&
	ufw status verbose &&
	return 0
}

configure_firewall_router() {
	ufw --force reset &&
	ufw enable &&
	ufw allow in on eno1 to any port 67 && # DHCP
	ufw allow in on eno1 to any port 53 && # DNS
	ufw allow in on eno1 to any port http &&
	ufw allow in on eno1 to any port ssh &&
	ufw allow in on wlp0s20f3 to any port ssh && # alleen SSH van WAN, gebruik SSH tunnel om vanaf WAN het LAN te bereiken
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

install_management_pages() {
	cp -r install/docs/ /var/www/ &&
	cp -r install/mgmnt/ /var/www/ &&
	chown -R www-data:www-data /var/www/docs/ &&
  chown -R www-data:www-data /var/www/mgmnt/ &&
	# allow the webserver (php script) to manage the wifi connection
	echo -e "www-data ALL=(ALL:ALL) NOPASSWD: /usr/bin/nmcli, /sbin/shutdown, /sbin/reboot, /bin/cp, /bin/systemctl" >> /etc/sudoers &&
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
#install_accesspoint &&
install_router &&
configure_firewall_router &&
install_cockpit &&
install_portainer &&
install_wordpress &&
install_gitbucket &&
install_management_pages &&
configure_apache &&
echo -e "\n\n====================\nKlaar! Zie https://github.com/coderdojonijmegen/MobieleCoderDojoServer voor instructies om Portainer, WordPress en GitBucket te configureren.\n====================\n\n"
