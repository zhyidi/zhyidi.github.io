# Ubuntu/Linux管理swap交换空间

列举一些近期遇到的内存问题：

- 在服务器上编译源码时，由于源码过大，在编译过程中被kill。使用htop查看程序运行的内存状态，发现是内存不够。
- 移植conda虚拟环境时报错：Collecting package metadata (repodata.json): | Killed。这也是内存不够问题。

#### 1. 是否需要添加swap交换空间

- 并不是物理RAM内存已满时才使用swap。即使您的Linux服务器有足够多的可用内存，您也会经常发现Linux服务器长时间运行后会使用交换空间。Linux内核会将几乎从未使用过的内存页面移动到交换空间中，以确保内存为更频繁使用内存页面的进程提供更多可用的缓存空间。
- 交换空间swap让管理员有时间对低内存问题做出反应。我们经常会注意到服务器运行缓慢，并且在登录时会注意到大量使用交换空间。如果没有交换空间，内存不足会产生更加突然和严重的连锁反应。
- 如果您没有足够的内存并且没有交换空间，会导致无法为需要更多内存的进程分配内存。作为最后的手段，内核将终止高内存使用的进程。
- 但是交换空间I/O的性能很差，Linux内核使用交换空间而不是RAM内存的时候会严重降低性能。如果Linux服务器确实有大量空闲可用的内存RAM，则应调整、禁用甚至删除交换空间。

请综合以上几点及服务器的现实情况，自行决定是否需要添加swap交换空间以及需要添加的空间大小！！！要更详细地了解Linux交换空间Swap，请阅读Linux内核文档中的[交换空间管理](https://www.kernel.org/doc/gorman/html/understand/understand014.html)和[Page Frame回收](https://www.kernel.org/doc/gorman/html/understand/understand013.html)文档。

#### 2. 创建swap交换空间

交换空间swap可以采用专用交换分区或交换文件的形式创建。 通常，在虚拟机或者云服务器上运行Ubuntu时，不存在交换分区，唯一的选择是创建交换文件。

fallocate或dd命令创建交换空间文件swapfile，大小是2G：

```
sudo fallocate -l 2G /swapfile
sudo dd if=/dev/zero of=/swapfile bs=1024 count=2097152
```

默认交换空间文件的权限只有root用户才能写入和读取交换文件的数据。因此我们需要修改交换空间文件的权限为600。除此之外你还需要格式化交换空间的文件。交换空间有自己的文件系统格式和专用格式化工具mkswap。

```
sudo chmod 600 /swapfile
sudo mkswap /swapfile
```

使用swapon命令启用交换空间文件，它将会在自动挂载到系统中。

```
sudo swapon /swapfile
```

swapon命令启用交换空间仅此次会话可用，重启后将不会自动挂载。为了让交换空间永久启用，并在开机启动时自动挂载，你需要在/etc/fstab文件中定义挂载配置选项。以下命令使用echo，tee命令以及管道追加行/swapfile swap swap defaults 0 0到/etc/fstab文件：

```
echo "/swapfile swap swap defaults 0 0" | sudo tee -a /etc/fstab
```

当计算机在重启时，交换空间将会自动启用。通过使用swapon或free命令验证交换空间是否处于活动状态。

```
sudo swapon --show
sudo free -h
```

#### 3. 删除swap交换空间

首先使用swapoff命令关闭交换空间。

```
sudo swapoff -v /swapfile
```

然后编辑/etc/fstab，移除交换空间自动挂载的行。

最后删除交换空间文件/swapfile即可。