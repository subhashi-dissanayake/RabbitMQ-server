Vagrant.configure(2) do |config|

  config.vm.provider :virtualbox do |vbox|
    vbox.gui = false
    vbox.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vbox.name = "rabbitmq-server"
  end

  config.vm.define "rabbitmq" do |rabbitmq|
    rabbitmq.vm.box = "ubuntu/trusty32"
    rabbitmq.vm.network "private_network", ip: "10.0.10.34"
    rabbitmq.vm.network :forwarded_port, guest: 5672, host: 5672, auto_correct: true
    rabbitmq.vm.network :forwarded_port, guest: 80, host: 8080, auto_correct: true
  end

  config.vm.provision :shell, inline: <<-SHELL
    sudo wget http://www.rabbitmq.com/releases/rabbitmq-server/v3.6.3/rabbitmq-server_3.6.3-1_all.deb
    sudo apt-get -f install -y
    sudo dpkg -i rabbitmq-server_3.6.3-1_all.deb
    sudo apt-get install php5-cli -y
  SHELL

 # config.vm.provision :shell, :inline => "cd /home/vagrant && sudo service rabbitmq-server start", run: "always"

end