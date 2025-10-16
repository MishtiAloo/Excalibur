

## 🧩 **RELATIONSHIPS EXPLAINED**

---

### **1️⃣ users ↔ officers / volunteers / specialVolunteers**

| From                                                               | To        | Relation Type                               | Description |
| ------------------------------------------------------------------ | --------- | ------------------------------------------- | ----------- |
| `officers.officer_id → users.user_id`                              | 1-to-1    | Every officer is a user.                    |             |
| `volunteers.volunteer_id → users.user_id`                          | 1-to-1    | Every volunteer is a user.                  |             |
| `specialVolunteers.special_volunteer_id → volunteers.volunteer_id` | 1-to-1    | Each special volunteer is also a volunteer. |             |
| `specialVolunteers.verified_by_officer → officers.officer_id`      | many-to-1 | Verified by one officer.                    |             |

---

### **2️⃣ users ↔ skills (via user_skills)**

| From                                                                     | To        | Relation Type                     | Description |
| ------------------------------------------------------------------------ | --------- | --------------------------------- | ----------- |
| `user_skills.user_id → users.user_id`                                    | many-to-1 | A user can have many skills.      |             |
| `user_skills.skill_id → skills.skill_id`                                 | many-to-1 | A skill can belong to many users. |             |
| ✅ Effective relationship: **many-to-many** between `users` and `skills`. |           |                                   |             |

---

### **3️⃣ users ↔ cases**

| From                               | To        | Relation Type                                              | Description |
| ---------------------------------- | --------- | ---------------------------------------------------------- | ----------- |
| `cases.created_by → users.user_id` | many-to-1 | Each case created by one user; user can create many cases. |             |

---

### **4️⃣ cases ↔ searchGroups**

| From                                     | To        | Relation Type                           | Description |
| ---------------------------------------- | --------- | --------------------------------------- | ----------- |
| `searchGroups.case_id → cases.case_id`   | many-to-1 | A case can have multiple search groups. |             |
| `searchGroups.leader_id → users.user_id` | many-to-1 | A search group has one leader (user).   |             |

---

### **5️⃣ searchGroups ↔ group_members ↔ volunteers**

| From                                                                                | To        | Relation Type                     | Description |
| ----------------------------------------------------------------------------------- | --------- | --------------------------------- | ----------- |
| `group_members.group_id → searchGroups.group_id`                                    | many-to-1 | Each member belongs to one group. |             |
| `group_members.volunteer_id → volunteers.volunteer_id`                              | many-to-1 | Each group member is a volunteer. |             |
| ✅ Effective relationship: **many-to-many** between `searchGroups` and `volunteers`. |           |                                   |             |

---

### **6️⃣ officers ↔ instructions ↔ cases / groups**

| From                                            | To        | Relation Type                             | Description |
| ----------------------------------------------- | --------- | ----------------------------------------- | ----------- |
| `instructions.officer_id → officers.officer_id` | many-to-1 | Each instruction issued by one officer.   |             |
| `instructions.case_id → cases.case_id`          | many-to-1 | Instruction relates to one case.          |             |
| `instructions.group_id → searchGroups.group_id` | many-to-1 | Instruction assigned to one search group. |             |

---

### **7️⃣ cases ↔ reports ↔ users**

| From                              | To        | Relation Type                 | Description |
| --------------------------------- | --------- | ----------------------------- | ----------- |
| `reports.case_id → cases.case_id` | many-to-1 | A case can have many reports. |             |
| `reports.user_id → users.user_id` | many-to-1 | A user can file many reports. |             |

---

### **8️⃣ reports ↔ media_reports**

| From                                          | To        | Relation Type                             | Description |
| --------------------------------------------- | --------- | ----------------------------------------- | ----------- |
| `media_reports.report_id → reports.report_id` | many-to-1 | A report can have many media attachments. |             |
| `media_reports.uploaded_by → users.user_id`   | many-to-1 | Media uploaded by one user.               |             |

---

### **9️⃣ reports ↔ (tips / evidences / sightings / hazards / attacks)**

Each specialized report type extends the base report (1-to-1):

| From                                      | To     | Relation Type     | Description |
| ----------------------------------------- | ------ | ----------------- | ----------- |
| `tips.report_id → reports.report_id`      | 1-to-1 | Tip details.      |             |
| `evidences.report_id → reports.report_id` | 1-to-1 | Evidence details. |             |
| `sightings.report_id → reports.report_id` | 1-to-1 | Sighting details. |             |
| `hazards.report_id → reports.report_id`   | 1-to-1 | Hazard details.   |             |
| `attacks.report_id → reports.report_id`   | 1-to-1 | Attack details.   |             |

Extra:

| From                                          | To        | Relation Type                 | Description |
| --------------------------------------------- | --------- | ----------------------------- | ----------- |
| `tips.verified_by → officers.officer_id`      | many-to-1 | Verified by officer.          |             |
| `evidences.received_by → officers.officer_id` | many-to-1 | Evidence received by officer. |             |

---

### **🔟 cases ↔ alerts**

| From                                       | To        | Relation Type                    | Description |
| ------------------------------------------ | --------- | -------------------------------- | ----------- |
| `alerts.case_id → cases.case_id`           | many-to-1 | A case can have multiple alerts. |             |
| `alerts.approved_by → officers.officer_id` | many-to-1 | Approved by one officer.         |             |

---

### **11️⃣ resources ↔ resource_bookings ↔ searchGroups / users**

| From                                                                               | To        | Relation Type                              | Description |
| ---------------------------------------------------------------------------------- | --------- | ------------------------------------------ | ----------- |
| `resource_bookings.resource_id → resources.resource_id`                            | many-to-1 | One resource can be booked multiple times. |             |
| `resource_bookings.group_id → searchGroups.group_id`                               | many-to-1 | Each booking assigned to one group.        |             |
| `resource_bookings.checked_out_by → users.user_id`                                 | many-to-1 | Checked out by one user.                   |             |
| ✅ Effective relationship: **many-to-many** between `resources` and `searchGroups`. |           |                                            |             |

---

### **12️⃣ users ↔ notifications**

| From                                    | To        | Relation Type                          | Description |
| --------------------------------------- | --------- | -------------------------------------- | ----------- |
| `notifications.user_id → users.user_id` | many-to-1 | A user can receive many notifications. |             |

---

## 📘 **Summary Table**

| #  | From                                               | To                                   | Type |
| -- | -------------------------------------------------- | ------------------------------------ | ---- |
| 1  | users → officers                                   | 1-to-1                               |      |
| 2  | users → volunteers                                 | 1-to-1                               |      |
| 3  | volunteers → specialVolunteers                     | 1-to-1                               |      |
| 4  | officers → specialVolunteers                       | 1-to-many (verified_by_officer)      |      |
| 5  | users ↔ skills                                     | many-to-many (via user_skills)       |      |
| 6  | users → cases                                      | 1-to-many (created_by)               |      |
| 7  | cases → searchGroups                               | 1-to-many                            |      |
| 8  | users → searchGroups                               | 1-to-many (leader_id)                |      |
| 9  | searchGroups ↔ volunteers                          | many-to-many (via group_members)     |      |
| 10 | officers → instructions                            | 1-to-many                            |      |
| 11 | cases → instructions                               | 1-to-many                            |      |
| 12 | searchGroups → instructions                        | 1-to-many                            |      |
| 13 | cases → reports                                    | 1-to-many                            |      |
| 14 | users → reports                                    | 1-to-many                            |      |
| 15 | reports → media_reports                            | 1-to-many                            |      |
| 16 | reports → tips/evidences/sightings/hazards/attacks | 1-to-1                               |      |
| 17 | officers → tips/evidences                          | 1-to-many                            |      |
| 18 | cases → alerts                                     | 1-to-many                            |      |
| 19 | officers → alerts                                  | 1-to-many                            |      |
| 20 | resources ↔ searchGroups                           | many-to-many (via resource_bookings) |      |
| 21 | users → resource_bookings                          | 1-to-many (checked_out_by)           |      |

---