

# hyperf 快速搭建
> docker搭建 hyperf项目 + redis + mysql



### 1. 启动
> 启动容器

```shell
# 1. 启动
docker-compose up -d

# 2. 查看运行没有
docker ps -a

# 3. 进入hyper容器
docker exec -it my-hyperf bash
cd data/project/hyperf-skeleton/

# 4. 安装依赖
composer install

# 5. 运行
php bin/hyperf.php start
```

