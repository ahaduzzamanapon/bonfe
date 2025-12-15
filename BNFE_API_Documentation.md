# BNFE API Documentation

**Date:** 14-12-2025
**URL will be:** `http://bnfe.mysoftheaven.com`

---

## 1. Get Occupations

| | |
|---|---|
| **API Name** | Get Occupations |
| **Details** | Returns a list of all occupations. |
| **Method** | `GET` |
| **Request object** | N/A |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_occupations` |
| **Request format** | N/A |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 3,
            "name": "General"
        },
        {
            "id": 4,
            "name": "Computer Operation and Graphics Design"
        }
    ]
}
```

---

## 2. Get Training Centers

| | |
|---|---|
| **API Name** | Get Training Centers |
| **Details** | Returns a list of all training centers. |
| **Method** | `GET` |
| **Request object** | N/A |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_training_center` |
| **Request format** | N/A |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 4,
            "insatitute_name": "Kisholoy Adorsho Shikhkha Neketon",
            "district": {
                "id": 12,
                "name_bn": "কক্সবাজার",
                "name_en": "Cox's Bazar"
            },
            "address": "Kisholoy Adorsho Shikhkha Neketon",
            "status": "Active"
        }
    ]
}
```

---

## 3. Get Training Center by District ID

| | |
|---|---|
| **API Name** | Get Training Center by District ID |
| **Details** | Returns a list of training centers for a given district_id. |
| **Method** | `POST` |
| **Request object** | `district_id` |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_training_center_by_district_id` |
| **Request format** | JSON |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Request Body:**
```json
{
    "district_id": 12
}
```

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 4,
            "insatitute_name": "Kisholoy Adorsho Shikhkha Neketon",
            "district": {
                "id": 12,
                "name_bn": "কক্সবাজার",
                "name_en": "Cox's Bazar"
            },
            "address": "Kisholoy Adorsho Shikhkha Neketon",
            "status": "Active"
        }
    ]
}
```

---

## 4. Get Learner

| | |
|---|---|
| **API Name** | Get Learner |
| **Details** | Returns a list of learners. Supports pagination and fetching all learners at once. All learners are filtered to be on or after 2025-12-14. |
| **Method** | `GET` |
| **Request object** | `page`, `per_page`, `all` (optional) |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_learner` |
| **Request format** | Query Parameters |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Query Parameters:**
- `page` (optional): The page number for pagination.
- `per_page` (optional): The number of items per page for pagination.
- `all` (optional): Set to `true` to get all learners at once.

**Sample Response (Paginated):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "registration_number": "REG-LE-MPS-00001",
                "candidate_id": "CBPI-EBC-MPS-000001",
                "candidate_name": null,
                "candidate_name_bn": "মোঃ শাহরিয়াজ হক তামিম ",
                "brn": null,
                "father_name": null,
                "mother_name": null,
                "image": null,
                "attachment": null,
                "nid": null,
                "address": null,
                "date_of_birth": "2008-02-04T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "1970-01-01T00:00:00.000000Z",
                "assessment_center": null,
                "assessment_center_registration_number": null,
                "age": "16",
                "literacy_status": "সাবলীল",
                "educational_qualification": "৬ষ্ঠ",
                "training_start_date": "2024-04-15",
                "training_end_date": "1970-01-01",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Fail",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": null,
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            },
            {
                "id": 2,
                "registration_number": "REG-LE-MPS-00002",
                "candidate_id": "CBPI-EBC-MPS-000002",
                "candidate_name": "Mofiz Alam",
                "candidate_name_bn": "মফিজ আলম",
                "brn": null,
                "father_name": "Md. Alam",
                "mother_name": "Rokia Begum",
                "image": null,
                "attachment": null,
                "nid": null,
                "address": "Samity Para Ward No. 6 South Mithachari",
                "date_of_birth": "2007-01-24T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "2024-10-17T00:00:00.000000Z",
                "assessment_center": {
                    "id": 7,
                    "center_name": "Cox's Bazar Polytechnic Institute",
                    "registration_number": "AC814697",
                    "address": "Cox's Bazar Polytechnic Institute"
                },
                "assessment_center_registration_number": null,
                "age": "17",
                "literacy_status": "সাবলীল",
                "educational_qualification": " ৮ম",
                "training_start_date": "2024-04-15",
                "training_end_date": "2024-10-17",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Passed",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": {
                    "id": 188,
                    "insatitute_name": "Mohammadia Telecom",
                    "district": 12,
                    "address": "Mohammadia Telecom",
                    "status": "Active",
                    "description": "Mohammadia Telecom"
                },
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            },
            {
                "id": 3,
                "registration_number": "REG-LE-MPS-00003",
                "candidate_id": "CBPI-EBC-MPS-000003",
                "candidate_name": "Ronayed Islam Saimun",
                "candidate_name_bn": "রোনায়েদ ইসলাম সাইমুন",
                "brn": null,
                "father_name": "Zafar Alam",
                "mother_name": "Rezia Begum",
                "image": null,
                "attachment": null,
                "nid": null,
                "address": "North Muhuri Para Link Road Cox's Bazar",
                "date_of_birth": "2006-11-25T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "2024-10-17T00:00:00.000000Z",
                "assessment_center": {
                    "id": 7,
                    "center_name": "Cox's Bazar Polytechnic Institute",
                    "registration_number": "AC814697",
                    "address": "Cox's Bazar Polytechnic Institute"
                },
                "assessment_center_registration_number": null,
                "age": "17",
                "literacy_status": "সাবলীল",
                "educational_qualification": " ৮ম",
                "training_start_date": "2024-04-15",
                "training_end_date": "2024-10-17",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Passed",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": {
                    "id": 188,
                    "insatitute_name": "Mohammadia Telecom",
                    "district": 12,
                    "address": "Mohammadia Telecom",
                    "status": "Active",
                    "description": "Mohammadia Telecom"
                },
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            },
            {
                "id": 4,
                "registration_number": "REG-LE-MPS-00004",
                "candidate_id": "CBPI-EBC-MPS-000004",
                "candidate_name": "Erfanul Islam",
                "candidate_name_bn": "এরফানুল ইসলাম ",
                "brn": null,
                "father_name": "Abu Taher",
                "mother_name": "Jannat Begum",
                "image": null,
                "attachment": null,
                "nid": null,
                "address": "Putibila Gorakhata Maheshkhali Municipality",
                "date_of_birth": "2007-03-20T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "2024-10-17T00:00:00.000000Z",
                "assessment_center": {
                    "id": 7,
                    "center_name": "Cox's Bazar Polytechnic Institute",
                    "registration_number": "AC814697",
                    "address": "Cox's Bazar Polytechnic Institute"
                },
                "assessment_center_registration_number": null,
                "age": "17",
                "literacy_status": "সাবলীল",
                "educational_qualification": "৭ম",
                "training_start_date": "2024-04-15",
                "training_end_date": "2024-10-17",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Passed",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": {
                    "id": 189,
                    "insatitute_name": "Mubin Telecom",
                    "district": 12,
                    "address": "Mubin Telecom",
                    "status": "Active",
                    "description": "Mubin Telecom"
                },
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            },
            {
                "id": 5,
                "registration_number": "REG-LE-MPS-00005",
                "candidate_id": "CBPI-EBC-MPS-000005",
                "candidate_name": "Shaker Mia",
                "candidate_name_bn": "শাকের মিয়া",
                "brn": null,
                "father_name": "Mujib Mia",
                "mother_name": "Rasheda Begum",
                "image": null,
                "attachment": null,
                "nid": null,
                "address": "Meheriapara Ward No. 8 Kutubjom Maheshkhali",
                "date_of_birth": "2006-07-28T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "2024-10-17T00:00:00.000000Z",
                "assessment_center": {
                    "id": 7,
                    "center_name": "Cox's Bazar Polytechnic Institute",
                    "registration_number": "AC814697",
                    "address": "Cox's Bazar Polytechnic Institute"
                },
                "assessment_center_registration_number": null,
                "age": "18",
                "literacy_status": "সাবলীল",
                "educational_qualification": "৬ষ্ঠ ",
                "training_start_date": "2024-04-15",
                "training_end_date": "2024-10-17",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Passed",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": {
                    "id": 189,
                    "insatitute_name": "Mubin Telecom",
                    "district": 12,
                    "address": "Mubin Telecom",
                    "status": "Active",
                    "description": "Mubin Telecom"
                },
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            },
            {
                "id": 6,
                "registration_number": "REG-LE-MPS-00006",
                "candidate_id": "CBPI-EBC-MPS-000006",
                "candidate_name": "Arif Ullah",
                "candidate_name_bn": "আরিফ উল্লাহ ",
                "brn": null,
                "father_name": "Ashraf Zaman",
                "mother_name": "Paradise Ara",
                "image": null,
                "attachment": null,
                "nid": null,
                "address": "North Dickpara P.M. Khali Cox's Bazar Sadar",
                "date_of_birth": "2007-03-10T00:00:00.000000Z",
                "mobile_number": 0,
                "email": null,
                "admitted_from": null,
                "assessment_date": "2024-10-17T00:00:00.000000Z",
                "assessment_center": {
                    "id": 7,
                    "center_name": "Cox's Bazar Polytechnic Institute",
                    "registration_number": "AC814697",
                    "address": "Cox's Bazar Polytechnic Institute"
                },
                "assessment_center_registration_number": null,
                "age": "17",
                "literacy_status": "সাবলীল",
                "educational_qualification": "৭ম",
                "training_start_date": "2024-04-15",
                "training_end_date": "2024-10-17",
                "gender": "ছেলে ",
                "status": "Chairman Approved",
                "exam_status": "Passed",
                "exam_result_sheet": null,
                "chairmen_status": "Approved",
                "controller_id": 7,
                "districts_admin_id": 2,
                "districts_admin_status": "Approved",
                "controller_back_comments": null,
                "notified": "Pending",
                "occupation": {
                    "id": 8,
                    "title": "Mobile Phone Servicing",
                    "description": "মোবাইল ফোন সার্ভিসিং"
                },
                "district": {
                    "id": 12,
                    "name_bn": "কক্সবাজার",
                    "name_en": "Cox's Bazar"
                },
                "upazila": {
                    "id": 383,
                    "dis_id": 12,
                    "name_en": "Cox's Bazar Sadar",
                    "name_bn": "কক্সবাজার সদর"
                },
                "training_center": {
                    "id": 189,
                    "insatitute_name": "Mubin Telecom",
                    "district": 12,
                    "address": "Mubin Telecom",
                    "status": "Active",
                    "description": "Mubin Telecom"
                },
                "chairman": null,
                "program": {
                    "id": 1,
                    "program_title": "Pre Vocational (Batch-01)",
                    "program_type": "Technical",
                    "start_date": "2016-12-01T00:00:00.000000Z",
                    "end_date": "2016-12-31T00:00:00.000000Z",
                    "description": "testing gg"
                }
            }
        ],
        "first_page_url": "http://bnfe.mysoftheaven.com/api/v1/get_learner?page=1",
        "from": null,
        "last_page": 1,
        "last_page_url": "http://bnfe.mysoftheaven.com/api/v1/get_learner?page=1",
        "links": [],
        "next_page_url": null,
        "path": "http://bnfe.mysoftheaven.com/api/v1/get_learner",
        "per_page": 10,
        "prev_page_url": null,
        "to": null,
        "total": 0
    }
}
```

---

## 5. Get Learner by District

| | |
|---|---|
| **API Name** | Get Learner by District |
| **Details** | Returns a list of learners for a given district_id. All learners are filtered to be on or after 2025-12-14. |
| **Method** | `POST` |
| **Request object** | `district_id` |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_learner_by_district` |
| **Request format** | JSON |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Request Body:**
```json
{
    "district_id": 12
}
```

**Sample Response:**
```json
{
    "success": true,
    "data": []
}
```

---

## 6. Get Learner by Upazila

| | |
|---|---|
| **API Name** | Get Learner by Upazila |
| **Details** | Returns a list of learners for a given upazila_id. All learners are filtered to be on or after 2025-12-14. |
| **Method** | `POST` |
| **Request object** | `upazila_id` |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_learner_by_upazila` |
| **Request format** | JSON |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Request Body:**
```json
{
    "upazila_id": 377
}
```

**Sample Response:**
```json
{
    "success": true,
    "data": []
}
```

---

## 7. Get Programs

| | |
|---|---|
| **API Name** | Get Programs |
| **Details** | Returns a list of all programs. |
| **Method** | `GET` |
| **Request object** | N/A |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_programs` |
| **Request format** | N/A |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "program_title": "Pre Vocational (Batch-01)",
            "program_type": "Technical"
        }
    ]
}
```

---

## 8. Get Districts

| | |
|---|---|
| **API Name** | Get Districts |
| **Details** | Returns a list of all districts. |
| **Method** | `GET` |
| **Request object** | N/A |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_districts` |
| **Request format** | N/A |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_en": "Bagerhat",
            "name_bn": "বাগেরহাট"
        }
    ]
}
```

---

## 9. Get Upazilas

| | |
|---|---|
| **API Name** | Get Upazilas |
| **Details** | Returns a list of all upazilas. |
| **Method** | `GET` |
| **Request object** | N/A |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_upazilas` |
| **Request format** | N/A |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name_en": "Kalihati",
            "name_bn": "কালিহাতী"
        }
    ]
}
```

---

## 10. Get Upazila by District

| | |
|---|---|
| **API Name** | Get Upazila by District |
| **Details** | Returns a list of upazilas for a given district_id. |
| **Method** | `POST` |
| **Request object** | `district_id` |
| **Response** | `200 OK` |
| **Method name/URL** | `/api/v1/get_upazila_by_district` |
| **Request format** | JSON |
| **Sample code** | |
| **API Key** | `woeifjW#^@%2-0,x0dk090Y(%&M@Y&*yoru8923ujioj2389r2389` |
| | |

**Request Body:**
```json
{
    "district_id": 12
}
```

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 377,
            "name_en": "Maheshkhali",
            "name_bn": "মহেশখালী"
        }
    ]
}
```
