# Mobile Application Subscription Management

### Architecture

- Runs on :8000
- Horizon Runs on :8000/horizon
- Register Device = [POST] => api/devices
- Subscription Check = [GET] => api/subscriptions
- Purchase = [POST] => api/subscriptions

## Stack

* **Laravel**
* **Laravel Horizon**
* **Redis**
* **PostgreSQL**
* **Docker**

## Used

* **Queues**
* **Commands & Task Scheduling**
* **Observer**
* **Events**
* **Listeners**
* **Service Container for Dependency Injection**
* **Http::fake for mocking servers**

### Requirements for local installation

- Docker

### Installation

```
  docker-compose up -d
```

### After Docker Installation

```
  docker-compose exec api bash
  php artisan migrate:fresh --seed
  php artisan horizon
```

### Information

```
1) First of all import masm_api.postman_collection.json on your postman
2) Should be used appname facebook,whatsapp and instagram for device registration
3) php artisan check:subscription runs on everyday to cancel expired subscriptions. for test just execute it
```