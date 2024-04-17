# Ubuntu命令终端模式下基于clash魔法上网

为改善服务器上利用conda命令安装包、wget下载某些资源时出现下载慢的情况，想到在服务器上配置魔法上网，但由于服务器并未安装GUI界面，故本篇文章记录自己在命令终端模式下基于clash设置魔法上网的过程。

### 1.下载并编辑clash

- 自行前往[该地址](https://release.dreamacro.workers.dev/latest/)或者[github](https://github.com/Dreamacro/clash/)上下载对应版本，可以通过wget下载，也可本地下载之后通过scp、sftp等工具上传到服务器。

- 为方便后续操作把下载下来的文件解压后更名为clash，同时可以移至/opt/clash文件夹。

- 进入/opt/clash给clash文件加执行权限。

  ```
  sudo chmod +x clash
  ```

- 此时clash已经能运行了，但无法正常启动，因为缺少`Country.mmdb`文件，下载该文件并移动至~/.config/clash下。

  ```
  wget -O Country.mmdb https://www.sub-speeder.com/client-download/Country.mmdb
  ```

### 2.获取配置文件

clash运行期间需要加载配置文件（即订阅配置），配置文件也要放在~/.config/clash下。

- 可以用wget获取配置文件：

  ```
  wget -O config.yaml [订阅链接]
  ```

- 但一些代理商的订阅链接打开是没有规律的乱码，此时就自己新建并编辑配置文件，把windows clash中已订阅的配置文件内容复制进去。

### 3.测试运行clash

- 设置代理

  ```
  export http_proxy=http://127.0.0.1:7890
  export https_proxy=http://127.0.0.1:7890
  ```

- 启用clash

  ```
  nohup /opt/clash/clash -f /home/ubuntu/.config/clash/config.yaml > /dev/null 2>&1 &
  ```

- 测试clash

  ```
  curl google.com
  ```

  如果出现结果即成功。

### 4.配置clash快速启动

编辑~/.bashrc文件，添加以下内容：

```
alias proxy='export http_proxy=http://127.0.0.1:7890;export https_proxy=http://127.0.0.1:7890'
alias un_proxy='unset http_proxy;unset https_proxy'
alias clash='nohup /opt/clash/clash -f /home/ubuntu/.config/clash/config.yaml > /dev/null 2>&1 &'
alias un_clash='pkill -9 clash'
```

编译一下:

```
source ~/.bashrc
```

以后开启魔法上网只需输入两个命令：proxy和clash，关闭魔法上网也只需输入两个命令：un_clash和un_proxy。

### 5. 设置定时更新订阅链接

使用`crontab -e`添加计划任务（下面的例子是每12小时更新一次）：

```
0 */12 * * * wget -O /home/ubuntu/.config/clash/config.yaml [订阅链接] > /home/ubuntu/.config/clash/config.log 2>&1
```

