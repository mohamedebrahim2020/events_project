<img alt="Drupal Logo" src="https://www.drupal.org/files/Wordmark_blue_RGB.png" height="60px">

Drupal is an open source content management platform supporting a variety of
websites ranging from personal weblogs to large community-driven websites. For
more information, visit the Drupal website, [Drupal.org][Drupal.org], and join
the [Drupal community][Drupal community].

# Events Management Module

The **Events Management** module is a custom Drupal 10 module that allows site administrators to create, manage, and display events on both the back-end and front-end. It also provides a configurable interface and logging for configuration changes.

---

## 📦 Features

- Custom Event content entity with the following attributes:
  - Title
  - Image
  - Description
  - Start time
  - End time (with validation)
  - Category (taxonomy or dropdown)
- Admin CRUD UI for managing events
- Config page with:
  - Toggle to show/hide past events
  - Option to limit number of events on listing page
- Logs configuration changes to a custom database table
- Front-end:

## 📦 Documentation
  - Event Modlue config /admin/config/events-manager
  - Event listing page /admin/events
  - Event details page /admin/events/{event}/show
  - Event create page /admin/events/add
  - Event delete link /admin/events/{event}/delete
  - Event update page /admin/events/{event}/edit

---

## 🛠 Installation Steps

### 1. Clone the repository
```bash
git clone https://github.com/mohamedebrahim2020/events_management.git
```

### 2. install project
```bash
composer install
```
### 3. create table called events (with its attributes)

### 4. download project from drupal ui browser and define database

### 5. download cacert.pem file and put it int php/extras/ssl

### 6. add the following configuration to httpd-vhosts.conf
```bash
<VirtualHost *:80>
    ServerName events.local
    DocumentRoot "C:/Users/Public/tasks/events_project/web"

    <Directory "C:/Users/Public/tasks/events_project/web">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
### 7 . allow or include httpd-vhosts.conf in httpd.conf
```bash
# Virtual hosts
# Include conf/extra/httpd-vhosts.conf // remove # from this
```

### 8. start apache server 
```bash
httpd
```




