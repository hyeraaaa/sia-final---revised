-- Insert dummy data for year_level
INSERT INTO year_level (year_level)
VALUES 
    ('1st Year'),
    ('2nd Year'),
    ('3rd Year'),
    ('4th Year');

-- Insert dummy data for department
INSERT INTO department (department_name)
VALUES
('CICS'),
('CABE'),
('CAS'),
('CE'),
('CIT'),
('CTE');

-- Insert dummy data for course
INSERT INTO course (course_name)
VALUES
('BSBA'),
('BSMA'),
('BSP'),
('BAC'),
('BSIE'),
('BSIT-CE'),
('BSIT-Electrical'),
('BSIT-Electronic'),
('BSIT-ICT'),
('BSIT'),
('BSE');

-- Insert dummy data for admin (PostgreSQL uses single quotes for strings)
INSERT INTO admin (first_name, last_name, email, password, contact_number, department_id, role)
VALUES 
    ('John', 'Doe', 'dummydata@gmail.com', '$2y$10$CIYt0BgpMeJtD3HGxAdAXe/XQUbDxBr7S/50uUyp0GWMkq8jjMYcW', '1234567890', 1, 'superadmin');

