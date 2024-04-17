# 管理jupyter notebook内核

#### 一、 添加内核：

```
#1.安装ipykernel
conda install ipykernel
#2.激活虚拟环境
conda activate pytorch
#3.将环境写入notebook的kernel中
python -m ipykernel install --user --name kernelname –display-name 'displayname'
```

但是执行第3步时会出现一个问题：该命令会修改jupyter的配置，以普通用户身份执行，由于配置文件是root的，无权修改；通过sudo或root用户执行，默认的python就不是环境中的python了，因为用户创建虚拟环境时，环境会安装在用户家目录，供单用户使用（当然如果配置了多用户共享虚拟环境便不存在这个问题了）。

可以使用以下方式解决：

- 使用which python查找python命令路径

  ```
  which python
  ```

- 使用python命令路径代替第3步的python重新执行命令。

  ```
  sudo /usr/local/miniconda3/bin/python -m ipykernel install --user --name kernelname –display-name 'displayname'
  ```

#### 二、 查看内核

```
jupyter kernelspec list
```

#### 三、 删除内核

```
jupyter kernelspec remove kernelname
```

