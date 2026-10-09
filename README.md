# irm-cancer-detector
Medical IRM cancer detector — CI4 + Java Servlet + MySQL, DDD 4-layer, Docker.

# exam-project — Medical Scan (IRM) mini-architecture

## Theme
Cancer detection from IRM scans. Domain entity: `Scan(id, patientRef, resultat)`.

## Stack
- MySQL 8 (container `db`, host port 3307)
- Java Servlet on Tomcat (container `java-service`, host port 8080)
- CodeIgniter 4 on Apache/PHP 8.2 (container `php-app`, host port 8081)

## Architecture
DDD 4 layers on both sides: domain / application / infrastructure / interfaces.

## Flows
- Flow A: Postman → CI4 `GET /api/scans` → Java `GET /api/scans` → JDBC → JSON back, CI4 also writes `writable/data/scans.json`.
- Flow B: Postman → Java `POST /api/scans` → JDBC insert → Java calls CI4 `POST /api/logs` → CI4 appends to `writable/data/activity.log`.

## Ports (host ↔ container)
| Service | Host | Container |
|---|---|---|
| MySQL | 3307 | 3306 |
| Java  | 8080 | 8080 |
| CI4   | 8081 | 80 |

## Rule
Between containers, always use service names (`db`, `java-service`, `php-app`). Never `localhost`.

# Updated TODO

- [x] Step 0 — GitHub repo + skeleton
- [x] Step 1 — Docker (mysql + tomcat + ci4)
- [x] Step 2 — Java domain + application
- [x] Step 3 — Java JDBC
- [x] Step 4 — Java servlet + JSON
- [x] Step 5 — CI4 domain + file access
- [x] **Step 6 — CI4 → Java (Flow A) + minimal UI page** 🔄
- [x] Step 7 — Java → CI4 (Flow B, `/api/logs`)
- [ ] **Step 8 — MRI image upload + mean-intensity heuristic** 🆕
- [ ] Step 9 — Full test from zero
- [ ] Step 10 — README + tag `v1.0`

---

Say **next** to resume **Step 6** (with the HTML page included — table + refresh button, no form yet).