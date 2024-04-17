# 红米AC2100刷OpenWrt第三方固件的详细教程

<font color=red>刷机有风险，后果自负！！！</font>

重要提示：

- 2022-07-24 Breed进行了重大更新，OpenWrt不再支持直接用底包刷固件，仅支持通过kernel1和rootfs0刷入固件；不再需要添加环境变量xiaomi.r3g.bootfw，旧版本如果进不去OpenWrt就需要将其值设置为2，其目的在于使Breed启动后从kernel1启动，现在OpenWrt、Padavan和原厂固件默认从kernel1启动，PandoraBox固件默认从kernel0启动。
- 提供更完善的 NAND 支持：现在使用的 NAND 的版本均支持完善的坏块管理功能，包括升级时自动跳过坏块、备份编程器固件时自动跳过坏块。



### 一、刷机前的准备

#### 1.下载有漏洞的官方固件

[红米ac2100 2.0.7](http://cdn.cnbj1.fds.api.mi-img.com/xiaoqiang/rom/rm2100/miwifi_rm2100_firmware_d6234_2.0.7.bin)

#### 2.下载openwrt内核

[官方地址](https://openwrt.org/toh/views/toh_fwdownload?dataflt%5BModel*%7E%5D=AC2100)

在官网下载如下两个文件。

![openwrt内核](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt%E5%86%85%E6%A0%B8.png)

#### 3.下载第三方OpenWrt正式固件

网上有很多第三方固件，这里推荐[supes.top](https://supes.top)定制固件，进入网页搜索Xiaomi Redmi Router AC2100下载这个文件（可选定制）。

![openwrt固件](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt%E5%9B%BA%E4%BB%B6.png)

### 二、刷入breed

#### 1.路由器降级

1. 进入小米路由器[原始管理页](192.168.31.1)
2. 常用设置->系统状态->手动升级->上传刚刚下载的有漏洞的官方固件（2.0.7版本）->开始升级(可勾选清除当前用户所有配置)
3. 等待升级完成后，连上降级后的wifi（正常是redmi开头无密码），再次进入[原始管理页](192.168.31.1)查看版本是否正确（2.0.7版本）

#### 2.正式刷入breed

此步刷入的教程网上有很多，这里引用恩山论坛大佬（jinglei207）的[一键URL注入](https://www.right.com.cn/forum/thread-4066963-1-1.html)方法。只能说NB！

1. <font color=red>首先需要确保路由器有网络，有网络才能自动下载Breed。</font>

2. 进入后台192.168.31.1，复制自己的stok。

   ![openwrt-stok](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt-stok.png)

3. 用复制的stok替换掉下面链接中的CCCCCCCCCCC部分之后全部复制到浏览器中打开，跳转页面会显示返回 {"code":0} （如果显示其他则可能是没有进行路由器降级或者stok过期）。路由器开始下载 Breed 进行刷写，大约需要1~3分钟进行重启，等待路由器指示灯由蓝色变为橘黄色，然后再次变为蓝色进入系统，此时Breed已经刷入完毕。

   ```
   http://192.168.31.1/cgi-bin/luci/;stok=CCCCCCCCCCC/api/misystem/set_config_iotdev?bssid=Xiaomi&user_id=longdike&ssid=%0Acd%20%2Ftmp%0Acurl%20-o%20B%20-O%20https%3A%2F%2Fbreed.hackpascal.net%2Fr1286%2520%255b2020-10-09%255d%2Fbreed-mt7621-xiaomi-r3g.bin%20-k%20-g%0A%5B%20-z%20%22%24(sha256sum%20B%20%7C%20grep%20242d42eb5f5aaa67ddc9c1baf1acdf58d289e3f792adfdd77b589b9dc71eff85)%22%20%5D%20%7C%7C%20mtd%20-r%20write%20B%20Bootloader%0A
   ```

   ps : 如果Breed进行了更新，需要额外进行以下操作。

   - 打开 https://breed.hackpascal.net/ ，搜索xiaomi，找到 breed-mt7621-xiaomi-r3g.bin 并下载。

   - 打开 https://crypot.51strive.com/sha256_checksum.html 将刚才下载的文件添加进去，生成生成HASH值。 

   - 将以下字符串中##################的部分替换为新生成的HASH值。再次替换CCCCCCCCCCC部分以后复制到浏览器打开即可。 

     ```
     http://192.168.31.1/cgi-bin/luci/;stok=CCCCCCCCCCC/api/misystem/set_config_iotdev?bssid=Xiaomi&user_id=longdike&ssid=%0Acd%20%2Ftmp%0Acurl%20-o%20B%20-O%20https%3A%2F%2Fbreed.hackpascal.net%2Fr1286%2520%255b2020-10-09%255d%2Fbreed-mt7621-xiaomi-r3g.bin%20-k%20-g%0A%5B%20-z%20%22%24(sha256sum%20B%20%7C%20grep%20##################)%22%20%5D%20%7C%7C%20mtd%20-r%20write%20B%20Bootloader%0A
     ```

#### 3.启用Breed并设置

1. <font color=red>为防止路由器变砖，请在首次进入Breed时在固件备份一栏中将EEPROM和编程器固件备份保存。EEPROM保存着出厂信息，且每台设备均为唯一，包括路由器SN，MAC地址和无线相关参数。EEPROM数据丢失可能导致无线网无法使用。</font>

2. 拔下电源线，长按Reset键不放手重新插上电源，等待路由器指示灯为蓝色闪烁状态松手，此时已成功启用Breed后台。用网线连接电脑与路由器，等待获取到ip地址以后登录 192.168.1.1 即可进入Breed界面。

3. 在小米 R3G 设置中删除字段“normal_firmware_md5”，闪存布局选择为 "小米 R3G OpenWrt" 。

### 三、刷入openwrt固件

1. 在Breed界面固件更新里上传之前下载的 kernel1 和 rootfs0 文件，等待重启之后通过http://192.168.1.1/或http://openwrt.lan/进入openwrt后台，用户名为root。 

2. 依次点击进入菜单System -> Backup / Flash Firmware，点击 Flash image... 上传第三方OpenWrt正式固件。

   ![openwrt-set1](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt-set1.png)

3. 上传下载好的第三方固件。

   ![openwrt-set2](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt-set2.png)

   取消勾选 Keep settings and retain the current configuration 然后点击 Continue，等待路由器成功刷机并重启。 

   ![openwrt-set3](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt-set3.png)

4. 成功刷入后即可登录OpenWrt第三方固件后台，后台: 10.0.0.1  密码: root。

   ![openwrt](https://cdn.yidi.space/sakura/article_images/ac2100-openwrt/openwrt.png)

