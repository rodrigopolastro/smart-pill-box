DROP DATABASE IF EXISTS smart_pill_box;
CREATE DATABASE smart_pill_box;
USE smart_pill_box;

CREATE TABLE NURSING_HOMES (
    NSH_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    NSH_company_name VARCHAR(100) NOT NULL,
    NSH_email VARCHAR(100) NOT NULL,
    NSH_password VARCHAR(30) NOT NULL,
    NSH_registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP()
);

CREATE TABLE PEOPLE_IN_CARE (
    PIC_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    PIC_nursing_home_id INTEGER NOT NULL,
    PIC_first_name VARCHAR(30) NOT NULL,
    PIC_last_name VARCHAR(100) NOT NULL,
    PIC_birth_date DATE,
    PIC_height INTEGER,
    PIC_weight INTEGER,
    PIC_allergies VARCHAR(200),
    PIC_notes VARCHAR(500),
    PIC_registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),

    CONSTRAINT CHK_PEOPLE_IN_CARE_height CHECK (PIC_height > 0),
    CONSTRAINT CHK_PEOPLE_IN_CARE_weight CHECK (PIC_weight > 0),
    CONSTRAINT FK_NURSING_HOMES_PEOPLE_IN_CARE
        FOREIGN KEY (PIC_nursing_home_id)
        REFERENCES NURSING_HOMES (NSH_id)
        ON DELETE CASCADE
);

CREATE TABLE MEDICINES (
    MED_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    MED_name VARCHAR(100) NOT NULL,
    MED_description VARCHAR(300),
    MED_quantity_pills INTEGER NOT NULL,
    MED_price FLOAT,

    CONSTRAINT CHK_MEDICINES_quantity_pills CHECK (MED_quantity_pills > 0)
);

CREATE TABLE TREATMENTS (
    TTM_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    TTM_person_id INTEGER NOT NULL,
    TTM_medicine_id INTEGER NOT NULL,
    TTM_reason VARCHAR(100),
    TTM_usage_frequency VARCHAR(30) NOT NULL,
    TTM_start_date DATE NOT NULL,
    TTM_doses_per_day INTEGER NOT NULL,
    TTM_pills_per_dose INTEGER NOT NULL,
    TTM_total_usage_days INTEGER NOT NULL,
    
    CONSTRAINT CHK_TREATMENT_usage_frequency 
        CHECK (TTM_usage_frequency IN (
            'daily',
            'weekly',
            'monthly',
            'days_interval'
        )
    ),
    CONSTRAINT CHK_TREATMENT_doses_per_day CHECK (TTM_doses_per_day > 0),
    CONSTRAINT CHK_TREATMENT_pills_per_dose CHECK (TTM_pills_per_dose > 0),
    CONSTRAINT CHK_TREATMENT_total_usage_days 
        CHECK (TTM_total_usage_days > 0 OR TTM_total_usage_days = -1), 

	    
    CONSTRAINT FK_PEOPLE_IN_CARE_TREATMENT
        FOREIGN KEY (TTM_person_id)
        REFERENCES PEOPLE_IN_CARE (PIC_id)
        ON DELETE RESTRICT,
 
    CONSTRAINT FK_MEDICINES_TREATMENT
        FOREIGN KEY (TTM_medicine_id)
        REFERENCES MEDICINES (MED_id)
        ON DELETE RESTRICT
);

CREATE TABLE DOSES (
    DOS_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    DOS_treatment_id INTEGER NOT NULL,
    DOS_due_datetime TIMESTAMP NOT NULL,
    DOS_taken_datetime TIMESTAMP NULL DEFAULT NULL,
    DOS_was_taken BOOLEAN DEFAULT 0 NOT NULL,

    CONSTRAINT FK_TREATMENT_DOSES
        FOREIGN KEY (DOS_treatment_id)
        REFERENCES TREATMENT (TTM_id)
        ON DELETE CASCADE
);