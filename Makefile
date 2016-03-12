CONTAINERS = rabbit-server
RABBIT_HOST = 127.0.0.1:15673

stop:
	@-docker stop $(CONTAINERS)

clean: stop
	@-docker rm -fv $(CONTAINERS)

build:
	@docker build -t rabman .

composer:
	@docker run --rm -v $(CURDIR):/data -v $$HOME/.composer/cache:/cache imega/composer:1.3.0 update -o

mock_rabbit_server:
	@docker run -d --hostname my-rabbit -p 15673:15672 --name rabbit-server rabbitmq:3-management

test:
	@docker run --rm \
		-v $(CURDIR):/data \
		-w /data \
		--env="RABBIT_HOST=$(RABBIT_HOST)" \
		rabman vendor/bin/codecept --debug run functional
