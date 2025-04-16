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
  - Event listing page /admin/events
  - Event details page /admin/events/{event}/show
  - Event create page /admin/events/add
  - Event delete link /admin/events/{event}/delete
  - Event update page /admin/events/{event}/edit

---

## 🛠 Installation Steps

### 1. Clone the repository
```bash
git clone https://github.com/your-username/events_management.git

