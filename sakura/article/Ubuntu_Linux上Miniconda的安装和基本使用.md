# Ubuntu/Linux上Miniconda的安装和基本使用

### 一、 安装miniconda

#### 1. 下载miniconda

```
wget https://repo.anaconda.com/miniconda/Miniconda3-latest-Linux-x86_64.sh
```

[官网地址](https://docs.conda.io/en/latest/miniconda.html)

![miniconda_download](https://cdn.yidi.space/sakura/article_images/miniconda/miniconda_download.png)

#### 2. 安装miniconda

ps: 该过程中会提示输入安装路径。

```
bash Miniconda3-latest-Linux-x86_64.sh
```

安装完成后刷新环境变量：

```
source ~/.bashrc
```

如果用户名前面出现了一个(base)，代表激活了base环境，安装成功。

### 二、 miniconda常用命令

- 获取版本号

  ```
  conda -V
  conda --version
  ```

- 更新conda

  ```
  conda update conda
  ```

- 环境管理

  ```
  # 查看环境列表
  conda env list
  # 创建虚拟环境
  conda create -n <env_name> [python_version] [package_name]
  # 删除虚拟环境
  conda remove -n <env_name> --all
  # 激活虚拟环境
  conda activate <env_name>
  # 退出虚拟环境(进入环境状态下才可使用)
  conda deactivate
  ```

- 包管理

  ```
  # 查看所有包
  conda list -n <env_name>  # 若不指定-n，默认在当前的环境
  # 搜索某个包信息
  conda search <package_name>  # 查询包的版本
  # 安装
  conda install -n <env_name> -c 镜像地址 <package_name>  # 若不指定-n，默认在当前的虚拟环境
  conda install <package_name>
  conda install <package_name>=1.0.0  # 指定版本
  
  # 更新
  # 更新当前环境所有包
  conda update --all
  # 更新指定包
  conda update -n <env_name> <package_name>  # 若不指定-n，默认在当前的虚拟环境
  conda update <package_name>
  
  # 删除
  conda remove -n <env_name> <package_name>  # 若不指定-n，默认在当前的虚拟环境
  conda remove <package_name>
  ```

- 移植环境

  ```
  # 1.进入环境
  conda activate my_env
  # 2.打包环境
  conda env export > my_env.yaml
  pip freeze > requirements.txt
  # 3.将 my_env.yaml、requirements.txt 文件拷贝至目标服务器
  # 4.复现虚拟环境
  conda env create -f my_env.yaml
  pip install -r requirements.txt
  ```

- 配置镜像源

  ```
  # 查看已经添加的channels
  conda config --get channels
  conda config --show-sources
  
  # 添加清华镜像(安装一次，镜像也只配置一次)
  conda config --set ssl_verify yes
  conda config --set show_channel_urls yes
  conda config --add channels https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/main/
  conda config --add channels https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/free/
  conda config --add channels https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/conda-forge/
  conda config --add channels https://mirrors.tuna.tsinghua.edu.cn/anaconda/cloud/pytorch/
  # 执行完上述命令后，会生成 ~/.condarc
  
  # 删除镜像
  conda config --remove channels 'https://mirrors.tuna.tsinghua.edu.cn/anaconda/pkgs/free'
  
  # 恢复原源
  conda config --get channels
  ```

### 三、 conda常见问题

请访问[该地址](https://yidi.space/tips/)查看详情。