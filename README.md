# php-lambda-layer

## usage
1. Clone this repository.
    ```
    $ git clone git@github.com:stackery/php-lambda-layer.git
    ```
1. Change directory.
    ```
    $ cd php-lambda-layer
    ```
1. Build and make PHP Layer.
    ```
    $ docker run --rm -v $(pwd):/opt/layer amazonlinux:2017.03.1.20170812 /opt/layer/build.sh
    ```
1. ZIP files created in current directory.
    ```
    $ ls |grep zip
    function.zip
    runtime.zip
    ```

