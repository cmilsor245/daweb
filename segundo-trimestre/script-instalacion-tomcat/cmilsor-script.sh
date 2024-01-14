#!/bin/bash

# user creation
sudo useradd -m -d /opt/tomcat -U -s /bin/false tomcat

# machine update
sudo apt update -y && sudo apt upgrade -y && sudo apt auto-remove -y

# java installation
sudo apt install openjdk-17-jdk -y
sudo apt install default-jdk -y

# tomcat file download to /tmp
sudo wget https://dlcdn.apache.org/tomcat/tomcat-10/v10.1.18/bin/apache-tomcat-10.1.18.tar.gz -P /tmp

# tomcat file extraction
sudo tar xzvf /tmp/apache-tomcat-10*tar.gz -C /opt/tomcat --strip-components=1

# set ownership to the tomcat user
sudo chown -R tomcat:tomcat /opt/tomcat/

# give execute permissions to the tomcat binaries
sudo chmod -R u+x /opt/tomcat/bin

# add users to tomcat-users.xml
sudo sed -i '/<\/tomcat-users>/i \
  <role rolename="manager-gui" /> \
  <user username="manager" password="1234" roles="manager-gui" /> \
  \
  <role rolename="admin-gui" /> \
  <user username="admin" password="1234" roles="manager-gui,admin-gui" />' /opt/tomcat/conf/tomcat-users.xml

# comment remoteaddrvalve in manager file
sudo sed -i '/<Valve className="org.apache.catalina.valves.RemoteAddrValve" allow="127\\.\d+\.\d+\.\d+|::1|0:0:0:0:0:0:0:1""/ s/^/<!--/;s/$/-->/' /opt/tomcat/webapps/manager/META-INF/context.xml

# comment remoteaddrvalve in host-manager file
sudo sed -i '/<Valve className="org.apache.catalina.valves.RemoteAddrValve" allow="127\\.\d+\.\d+\.\d+|::1|0:0:0:0:0:0:0:1""/ s/^/<!--/;s/$/-->/' /opt/tomcat/webapps/host-manager/META-INF/context.xml

# configure tomcat.service
sudo tee /etc/systemd/system/tomcat.service > /dev/null <<EOL
[Unit]
Description=Tomcat
After=network.target

[Service]
Type=forking

User=tomcat
Group=tomcat

Environment="JAVA_HOME=/usr/lib/jvm/java-1.17.0-openjdk-amd64"
Environment="JAVA_OPTS=-Djava.security.egd=file:///dev/urandom"
Environment="CATALINA_BASE=/opt/tomcat"
Environment="CATALINA_HOME=/opt/tomcat"
Environment="CATALINA_PID=/opt/tomcat/temp/tomcat.pid"
Environment="CATALINA_OPTS=-Xms512M -Xmx1024M -server -XX:+UseParallelGC"

ExecStart=/opt/tomcat/bin/startup.sh
ExecStop=/opt/tomcat/bin/shutdown.sh

RestartSec=10
Restart=always

[Install]
WantedBy=multi-user.target
EOL

# reload systemd to pick up the new unit file
sudo systemctl daemon-reload

# start tomcat
sudo systemctl start tomcat

# enable tomcat to start on system boot
sudo systemctl enable tomcat

# allow incoming traffic on port 8080
sudo ufw allow 8080
