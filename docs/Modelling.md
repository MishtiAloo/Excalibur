### **users**

`user_id (PK)`, NID, name, email, phone, password, permanent_location (using point), current_location (point), role (enum: citizen/officer/volunteer/specialVolunteer/watchDog/group_leader), status (enum: active/suspended/inactive), info_credibility, responsiveness

---

### **officers**

`officer_id (PK, FK)`, badge_no, department, rank

---

### **volunteers**

`volunteer_id (PK, FK)`, vetting_status (enum: pending/approved/rejected), availability (enum: available/busy/inactive)

---

### **specialVolunteers**

`special_volunteer_id (PK, FK)`, terrain_type (enum: water/forest/hilltrack/urban), verified_by_officer (FK)

---

### **skills**

`skill_id (PK)`, type (enum: navigation, drone, boat, mountain, forest, medic, diver, canine_handler, leadership), description

---

### **user_skills**

`user_id (FK)`, skill_id (FK), _(PK composite: user_id + skill_id)_, level, verified

---

### **cases**

`case_id (PK)`, created_by (FK), case_type (enum: missing/wanted/hazard/attack), title, description, coverage_area (point), status (enum: active/under_investigation/resolved/closed), urgency (enum: low/medium/high/critical/national)

---

### **searchGroups**

`group_id (PK)`, case_id (FK), leader_id (FK), type (enum: citizen/covert/terrainSpecial) intensity (enum: basic/rigorous/extreme/pinpoint), status (enum: active/paused/completed), allocated_time

---

### **group_members**

`group_id (FK)`, volunteer_id (FK), _(PK composite: group_id + volunteer_id)_

---

### **instructions**

`instruction_id (PK)`, group_id (FK), case_id, officer_id (FK), details, issued_at

---

### **reports**

`report_id (PK)`, case_id (FK), user_id (FK), report_type (enum: tip/evidence/sighting/hazard/attack/general), description, location (point), timestamp, status (enum: pending/verified/ressponded/falsed/dismissed)

---

### **media_reports** _(for attaching photos/videos/voice)_

`media_id (PK)`, report_id (FK), url (cloudinary), uploaded_by (FK), uploaded_at, description

---

### **tips**

`report_id (PK, FK)`, credibility_score, verified_by (FK)

---

### **evidences**

`report_id (PK, FK)`, received, received_by (FK)

---

### **sightings**

`report_id (PK, FK)`, sighted_person, time_seen

---

### **hazards**

`report_id (PK, FK)`, hazard_type (enum: animal/collapse/chemical), severity (enum: low/medium/high)

---

### **attacks**

`report_id (PK, FK)`, attack_type (enum: robbery/assault/terror/other), victims_count, attacker

---

### **alerts**

`alert_id (PK)`, case_id (FK), alert_type (enum: amber/silver/red/yellow), status (enum: active/expired/cancelled), approved_by (FK), expires_at, message

---

### **resources**

`resource_id (PK)`, name, stored_area (point), condition (enum: new/good/moderate/old) availability (enum: available/in_use/delayed_checkout/under_maintenance), count, availableCount

---

### **resource_bookings**

`booking_id (PK)`, resource_id (FK), group_id (FK), checked_out_by (FK), check_out_time, check_in_time

---

### **notifications** _(for push alerts & reminders)_

`notification_id (PK)`, type (enum: alert/update/new_search_start), message
