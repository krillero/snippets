netsh interface ipv4 set dns name="Ethernet 2" static 8.8.8.8 primary
netsh interface ipv4 add dns name="Ethernet 2" 8.8.4.4 index=2