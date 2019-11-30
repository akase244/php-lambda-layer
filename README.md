# php-lambda-layer

## usage
1. Clone this repository.
    ```
    $ git clone git@github.com:akase244/php-lambda-layer.git
    ```
1. Change directory.
    ```
    $ cd php-lambda-layer
    ```
1. Build and make PHP Layer.
    ```
    $ docker run --rm -v $(pwd):/opt/layer amazonlinux:2018.03.0.20191014.0 /opt/layer/build.sh
    ```
1. ZIP files created in current directory.
    ```
    $ ls |grep zip
    postNippoCount.zip
    runtime.zip
    vendor.zip
    ```

