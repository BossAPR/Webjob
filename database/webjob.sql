-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2025 at 02:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webjob`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE `applicant` (
  `applicant_id` int(11) NOT NULL,
  `resume` text DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `old` int(11) DEFAULT NULL,
  `qualification` int(11) DEFAULT NULL,
  `course` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `start_date` varchar(250) DEFAULT NULL,
  `employment_type` int(250) DEFAULT NULL,
  `preferred_location` int(100) DEFAULT NULL,
  `work_eligibility` varchar(100) DEFAULT NULL,
  `expected_salary` int(11) DEFAULT NULL,
  `salary_type` varchar(100) DEFAULT NULL,
  `interested_job_type` varchar(100) DEFAULT NULL,
  `conscription` varchar(100) DEFAULT NULL,
  `work_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`applicant_id`, `resume`, `account_id`, `sex`, `old`, `qualification`, `course`, `experience`, `start_date`, `employment_type`, `preferred_location`, `work_eligibility`, `expected_salary`, `salary_type`, `interested_job_type`, `conscription`, `work_type`) VALUES
(1, NULL, 1, 0, 23, 5, 37, 5, '2 สัปดาห์', 1, 1, 'พลเมืองไทย/ผู้พำนักถาวร', 30000, 'รายเดือน', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'ผ่านเกณฑ์แล้ว', 'Onsite');

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `employee_type` varchar(100) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `subscribe_updates` tinyint(1) DEFAULT NULL,
  `found_from` enum('Google','Facebook','Twitter') NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_ad`
--

CREATE TABLE `job_ad` (
  `job_ad_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `job_name` varchar(255) NOT NULL,
  `qualification` int(11) NOT NULL,
  `course` int(11) NOT NULL,
  `sex` int(11) NOT NULL,
  `age_min` int(11) NOT NULL,
  `age_max` int(11) NOT NULL,
  `experience_min` int(11) NOT NULL,
  `job_type` int(11) NOT NULL,
  `job_workers` int(11) NOT NULL,
  `got_workers` int(11) NOT NULL,
  `verify_company` text NOT NULL,
  `job_salary` int(11) NOT NULL,
  `job_time` text NOT NULL,
  `job_welfare` text NOT NULL,
  `job_detail` text NOT NULL,
  `job_location` text NOT NULL,
  `job_province` text NOT NULL,
  `job_district` text NOT NULL,
  `job_logo` text NOT NULL,
  `job_create_at` text NOT NULL,
  `job_expire_at` text NOT NULL,
  `job_category` text NOT NULL,
  `job_status` text NOT NULL,
  `job_mail` varchar(255) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_ad`
--

INSERT INTO `job_ad` (`job_ad_id`, `company_name`, `job_name`, `qualification`, `course`, `sex`, `age_min`, `age_max`, `experience_min`, `job_type`, `job_workers`, `got_workers`, `verify_company`, `job_salary`, `job_time`, `job_welfare`, `job_detail`, `job_location`, `job_province`, `job_district`, `job_logo`, `job_create_at`, `job_expire_at`, `job_category`, `job_status`, `job_mail`, `account_id`) VALUES
(1, 'IT COMPANY', 'พนักงานขายไอที', 5, 22, 0, 22, 30, 1, 1, 3, 0, '', 20000, '9.00-17.00', 'Commission & Sales Incentive\r\nFive-day work week\r\nTelephone ( Voice & Data ) Allowance\r\nTransportation allowance\r\nค่าที่พัก (ต่างจังหวัด)\r\nค่าน้ำมันรถ, ค่าเดินทาง\r\nค่าเบี้ยเลี้ยง \r\nค่าเสื่อมยานพาหนะ\r\nค่าโทรศัพท์\r\nทำงานสัปดาห์ละ 5 วัน\r\nประกันสังคม\r\nมีเวลาการทำงานที่ยืดหยุ่น\r\nเที่ยวประจำปี หรือเลี้ยงประจำปี', 'รับผิดชอบการขายสินค้าIT และยอดขายสินค้าในกลุ่ม IT Commercial.\r\nดูแลกลุ่มลูกค้าSI ขนาดกลาง, ใหญ่\r\nให้ข้อมูลและนำเสนอสินค้าให้ตรงกลุ่มเป้าหมาย\r\nเน้นงาน Project\r\nทำการ Presentation ให้กับลูกค้าเป้าหมายและติดตามผลเพื่อปิดการขาย', '23 Sorachai Bld., 15th Fl., Unit 23/35, Soi Sukhumvit 63, Sukhumvit Rd., Klongton Nua, Wattana, Bangkok 10110', '1', 'เขตทวีวัฒนา', 'j21819.webp', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'การบริหารและการเงิน', 'approved', 'pubpuang1811@gmail.com', 1),
(2, 'IT COMPANY', 'Pre-Sales Software', 5, 22, 0, 25, 30, 3, 1, 4, 0, '', 30000, '9.00-17.00', 'กองทุนสำรองเลี้ยงชีพ\r\nทำงานสัปดาห์ละ 5 วัน\r\nประกันสังคม\r\nลาบวช\r\nเบี้ยขยัน, ค่าตอบแทนพิเศษ\r\nโบนัสตามผลงาน/ผลประกอบการ', '- Pre-Sales Consultant for software your role is to plan pre-sales strategy and present, together with demonstrate our work flow software to impress potential customers\r\n\r\n- You need to understand competitive landscape and decide winning strategies\r\n\r\n- Work with and support sales force in present & positioning the software\r\n\r\n- Analyze customers or prospective customers’ requirements & understand their business drivers/issues and able to articulate a value adding solution positioning our solution and services to best effect\r\n\r\n- Develop & maintain knowledge of industry, market, software and services\r\n\r\n- Prepare Proposal, Cost Model, POC, TOR and etc\r\n\r\n- Collaborate closely with Sales Team, Project Management Team, Software Development Team, and other stakeholders to design and optimize the solutions.', '191/40 CTI Tower 22nd Floor, Ratchadapisek New Rd., Klongtoey, Bangkok 10110', '1', 'เขตคลองเตย', 'j237643.webp', '2024-11-11 00:42:32', '', 'การบริหารและการเงิน', 'Pending', 'pubpuang1811@gmail.com', 2),
(3, 'IT COMPANY', 'Sales Manager', 5, 22, 0, 22, 30, 2, 1, 5, 0, '', 35000, '9.00-17.00', 'การฝึกอบรมและพัฒนาพนักงาน\r\nทำงานสัปดาห์ละ 5 วัน\r\nประกันสังคม\r\nเครื่องแบบพนักงาน, ชุดยูนิฟอร์ม\r\nเที่ยวประจำปี หรือเลี้ยงประจำปี\r\nโบนัสตามผลงาน/ผลประกอบการ\r\nโบนัสประจำปี', 'We are seeking a dynamic and results-driven Sales Manager / Presales / Account Executive (AE) to join our team. The successful candidate will be responsible for driving sales and managing client relationships for our Vansales Application Platform and Software Development Projects. This role requires a combination of sales expertise, technical knowledge, and strong interpersonal skills to effectively engage with potential clients and drive business growth.', 'โครงการโกลเด้นซิตี้ แจ้งวัฒนะ-เมืองทอง เลขที่ 63/59 ซอย 8 ตำบลบ้านใหม่ อำเภอปากเกร็ด จังหวัดนนทบุรี 11120', '3', 'ปากเกร็ด', 'j249691.webp', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'การบริหารและการเงิน', 'approved', 'pubpuang1811@gmail.com', 1),
(4, 'IT COMPANY', 'เจ้าหน้าที่ดูแลระบบเครือข่าย', 5, 36, 1, 25, 30, 1, 1, 2, 0, '', 20000, '8.00-16.00', 'กองทุนสำรองเลี้ยงชีพ\r\nการฝึกอบรมและพัฒนาพนักงาน\r\nคลับเฮ้าส์ สถานที่ออกกำลังกาย ตลอดอายุการทำงาน\r\nค่าที่พัก (ต่างจังหวัด)\r\nค่าน้ำมันรถ, ค่าเดินทาง\r\nค่ายินดีมงคลสมรส\r\nค่าเบี้ยเลี้ยง \r\nค่าเสื่อมยานพาหนะ\r\nค่าโทรศัพท์\r\nทำงานสัปดาห์ละ 5 วัน\r\nบัตรเงินสดวันเกิด\r\nประกันสังคม\r\nประกันอุบัติเหตุ\r\nมีเวลาการทำงานที่ยืดหยุ่น\r\nเครื่องแบบพนักงาน, ชุดยูนิฟอร์ม\r\nเงินช่วยเหลือค่าปลงศพ บิดา,มารดา,สามี,บุตร\r\nเงินช่วยเหลือฌาปนกิจ\r\nเที่ยวประจำปี หรือเลี้ยงประจำปี\r\nเบี้ยขยัน, ค่าตอบแทนพิเศษ\r\nโบนัสตามผลงาน/ผลประกอบการ', '1.ออกแบบพัฒนานำวิธีการตัง􀃊 ค่าและแก้ไขระบบเครือข่ายไปใช้งานเพื􀃉อรองรับความต้องการขององค์กร\r\n2.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงระบบสื􀃉อสารในองค์กร Mail Server , MS Exchange , Outlook, MS Team , IP Phone\r\n3.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงระบบควบคุม Computer Domain Active Directory\r\n4.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงระบบจัดเก็บข้อมูล Data Server , File Server , NAS , Backup Data\r\n5.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงความปลอดภัย Firewall\r\n6.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงระบบเครือข่าย Internet , Wireless, Lan Network , VPN\r\n7.ออกแบบพัฒนากำหนดค่าเพื􀃉อปรับปรุงระบบกล้องวงจรปิด CCTV\r\n8.ควบคุมดูแลโครงการคุมการติดตัง􀃊 ระบบ Network ร่วมกับ Supplier และ Vendor ทดสอบ UAT และ Go-Live ระบบ\r\n9.คิดค้นนวัตกรรมเพื􀃉อหาวิธีการพัฒนานระบบหรือปรับปรุงแก้ไขระบบให้กับองค์กร\r\n10.จัดเตรียมคู่มือและแนะนำผู้ใช้งานสำหรับการใช้งานระบบทีพ􀃉 ัฒนาขึน􀃊 เองเพื􀃉อใช้ในองค์กร', '8899 หมู่ที่ 4 ตำบลปลวกแดง อำเภอปลวกแดง จังหวัดระยอง 21140', '12', 'ปลวกแดง', 'j232102.webp', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 2),
(5, 'IT COMPANY', 'IT Support (Helpdesk)', 5, 36, 0, 21, 30, 1, 2, 4, 0, '', 15000, '6.00-15.00', '-', '•แก้ไขปัญหา Hardware, Software, Network, Server ผ่านโปรแกรม Remote\r\n• ให้คำแนะนำ คำปรึกษาและแก้ปัญหาเกี่ยวกับการใช้งานโปรแกรมและอุปกรณ์คอมพิวเตอร์\r\n• ตรวจสอบและบันทึกรายละเอียดการให้คำปรึกษาอย่างตรงเวลาและติดตามผลเพื่อให้ User เกิดความพึงพอใจ\r\n•แก้ไขและวิเคราะห์ปัญหา สาเหตุวิธีการแก้ไขเบื้องต้น', 'อาคารอิตัลไทยทาวเวอร์ ชั้นชั้ 18 ถนนเพชรบุรีตัดใหม่ เขตห้วยขวาง กรุงเทพมหานคร 10310', '1', 'เขตห้วยขวาง', 'images.png', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 1),
(6, 'IT COMPANY', 'Web Developer ( PHP, Laravel, MySQL )', 5, 36, 0, 22, 30, 1, 2, 1, 0, '', 30000, '9.00-17.00', 'ทำงานสัปดาห์ละ 5 วัน\r\nประกันสังคม\r\nโบนัสประจำปี\r\nปรับเงินเดือนประจำปี\r\nมีงานเลี้ยงปีใหม่\r\nโบนัสตามผลงาน', '• สามารถทำงานร่วมกับทีมงานได้ดี\r\n• วางแผน ออกแบบ และพัฒนาโปรแกรมตามที่กำหนด\r\n• พัฒนา Software หรือ Application ลงสู่ Production\r\n• วิเคราะห์ปัญหาและพัฒนาระบบให้มีประสิทธิภาพมากยิ่งขึ้น\r\n• สามารถแก้ไขและพัฒนาฟีเจอร์ใหม่ๆ ได้ตามความต้องการของลูกค้า', '45/1 โครงการ ชาร์ลส์เจน ชั้น 6 แขวงคลองจั่น เขตบางกะปิ กรุงเทพมหานคร 10240', '1', 'เขตบางกะปิ', 'images.jfif', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 1),
(7, 'IT COMPANY', 'Frontend Developer ( React JS )', 5, 36, 0, 22, 30, 1, 2, 1, 0, '', 40000, '9.00-17.00', 'ทำงานสัปดาห์ละ 5 วัน\r\nประกันสังคม\r\nโบนัสประจำปี\r\nปรับเงินเดือนประจำปี\r\nมีงานเลี้ยงปีใหม่\r\nโบนัสตามผลงาน', '• พัฒนา Frontend โดยใช้ ReactJS\r\n• รับผิดชอบการออกแบบและพัฒนา UX/UI ให้กับเว็บไซต์หรือระบบออนไลน์\r\n• มีความเข้าใจในหลักการ Responsive Web Design\r\n• พัฒนาเว็บไซต์ให้ใช้งานได้บนอุปกรณ์มือถือ\r\n• ทดสอบและแก้ไขบั๊กในเว็บไซต์\r\n• วางแผนและออกแบบระบบให้เป็นไปตามความต้องการ', '45/1 โครงการ ชาร์ลส์เจน ชั้น 6 แขวงคลองจั่น เขตบางกะปิ กรุงเทพมหานคร 10240', '1', 'เขตบางกะปิ', '202695.webp', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 1),
(8, 'IT COMPANY', 'IT supervisor', 5, 36, 0, 30, 30, 3, 1, 2, 0, '', 30000, '8.00-17.00', 'มีเวลาการทำงานที่ยืดหยุ่น\r\nค่าทำงานล่วงเวลา\r\nค่าใช้จ่ายในการเดินทาง\r\nประกันสังคม\r\nตามข้อตกลงของบริษัท\r\nสิทธิการเบิกค่าทันตกรรม\r\nเงินโบนัสตามผลงาน\r\nค่ายานพาหนะ', '1. จัดทำนโยบาย แผนการดำเนินงานของฝ่ายเทคโนโลยีสารสนเทศ ให้สอดคล้องกับวิสัยทัศน์ และแผนงานขององค์กร\r\n2. ศึกษา วิเคราะห์ ออกแบบ วางแผน พัฒนาและปรับปรุง ระบบเทคโนโลยีสารสนเทศ เพื่อรองรับการดำเนินงานธุรกิจขององค์กรทั้งระบบ\r\n3. บริหารจัดการ และควบคุมงบประมาณค่าใช้จ่ายที่เกี่ยวข้องกับ IT ทั้งหมดขององค์กร\r\n4. บำรุงรักษา ระบบงาน เครื่องแม่ข่าย และอุปกรณ์ที่เกี่ยวข้อง ให้สามารถใช้งานได้อย่างต่อเนื่องและมีประสิทธิภาพ\r\n5. ควบคุม ดูแลการติดตั้ง ปรับแต่ง และประสิทธิภาพของระบบความมั่นคงปลอดภัย\r\n6. กำหนด วางแผนในการพัฒนาผู้ใต้บังคับบัญชา เพื่อเพิ่มศักยภาพและองค์ความรู้ของทีมให้สอดคล้องกับจุดประสงค์ขององค์กร\r\n7. พัฒนาและวางแผนในการ Implement ระบบใหม่ในองค์กร\r\n8. บริหารจัดการสัญญาต่างๆ ของ Hardware, Software และ Network หรือ อื่นๆ ที่เกี่ยวข้องกับแผนก\r\n9. วางแผนและควบคุมการปฏิบัติงานภายใต้มาตรฐานคุณภาพและสิ่งแวดล้อม (ISO:9001,14001) และระบบมาตรฐานอื่นๆ ในอนาคต\r\n10. ดูแลงานโปรแกรม SAP และโปรแกรมสำหรับการงานต่าง ๆ เพื่อให้ระบบทำงานอย่างราบรื่นและมีประสิทธิภาพ\r\n11. ดูแลรับผิดชอบอุปกรณ์ IT ต่าง ๆ เช่น คอมพิวเตอร์ โทรศัพท์ Server และระบบ network เพื่อให้พร้อมใช้งานอยู่เสม\r\n13. บริหารจัดการทีมงาน IT เพื่อให้ทำงานได้อย่างมีประสิทธิภาพและบรรลุเป้าหมายที่ตั้งไว้', 'คิงบางกอก อินเตอร์เทรด จำกัด\r\n\r\n541,543,545 ถ.รามอินทรา แขวงท่าแร้ง เขตบางเขน จังหวัดกรุงเทพมหานคร 10220 ประเทศไทย', '1', 'เขตบางเขน', '167377.webp', '2024-11-11 00:42:32', '', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'Not approved', 'pubpuang1811@gmail.com', 1),
(9, 'IT COMPANY', 'Job Title Placeholder', 5, 36, 0, 21, 30, 3, 1, 3, 0, '', 20000, '8.00-16.00', 'Benefits Placeholder', 'Responsibilities Placeholder', 'Location Placeholder', '1', 'เขาชะเมา', 'j232102.webp', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 2),
(10, 'IT COMPANY', 'Senior Java Developer (International Environment)', 5, 36, 0, 22, 50, 5, 1, 1, 0, '', 30000, '9.00-17.00', '-', 'Responsibilities\r\nEngaging in the BE (Java/Spring Boot) developments within the development team.\r\nConstantly updating technical knowledge.\r\nTroubleshooting issues.\r\nBeing autonomous to make the right technical decisions in the course of its duties. \r\nUsing agile methodology, the Senior Java Developer will advise and participate in technical projects.\r\nProviding technical support to other team members.\r\nRequirements\r\nBachelor’s Degree in Computer Science/Engineering.\r\nHave more than 5 years of experience in software development.\r\nAbility to use JAVA / Spring Boot is a must.\r\nAbility to use Angular 2+ is preferred.\r\nEnglish is a must.', '2034/82 (18-02/2) Ital - Thai Tower, Bangkapi, Huaykwang, Thailand', '1', 'ห้วยขวาง', 'j30.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 1),
(11, 'IT COMPANY', 'Full stack development officer', 5, 36, 0, 23, 50, 2, 1, 2, 0, '', 0, '9.00-18.00', '- ประกันสังคม ประกันชีวิต และอุบัติเหตุ\r\n- ตรวจสุขภาพประจำปี\r\n- วันลาพักร้อน 6 วัน/ปี\r\n- สิทธิ์วันลาในวันคล้ายวันเกิดโดยได้รับค่าจ้างตามปกติ\r\n- เบี้ยขยันประจำเดือน\r\n- การทำงานแบบ Hybrid Working (บางตำแหน่ง)\r\n- ค่าน้ำมัน/ค่าเสื่อมสภาพรถ (บางตำแหน่ง)\r\n- Commission/Incentive (บางตำแหน่ง)\r\n- งานเลี้ยงบริษัทฯ/กิจกรรมพนักงานตลอดทั้งปี\r\n- ตรวจสุขภาพประจำปี\r\n- การอบรม และพัฒนา\r\n- ปรับเงินเดือนประจำปี\r\n- โบนัสตามผลประกอบการ\r\n- อาหารกลางวันฟรี (เดือนละมื้อ)\r\n- เลี้ยงอาหารประจำไตรมาส', '- Develop and maintain frontend applications.\r\n- Design, build, and maintain backend services using Golang.\r\n- Integrate frontend and backend components seamlessly to create a cohesive user experience.\r\n- Deploy, manage, and scale applications on AWS cloud infrastructure.\r\n- Write clean, maintainable, and well-documented code.\r\n- Optimize application performance and ensure responsiveness across different devices and platforms.\r\n- Apply modern software development principles to enhance system performance and efficiency.\r\n- Manage documentation related to the developed systems.\r\n- Troubleshoot and resolve system-related issues\r\n- Collaborate with relevant teams to understand project requirements and ensure systems are developed in line with company guidelines and best practices', 'บริษัท สตีลเบสท์บาย จำกัด\r\nอาคาร 66 ทาวเวอร์ ห้องเลขที่ 503-504 ชั้นที่ 5 ถนนสุขุมวิท\r\nแขวงบางนาเหนือ เขตบางนา กรุงเทพมหานคร 10260', '1', 'เขตบางนา', 'j31.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 2),
(12, 'IT COMPANY', 'Database Administrator - งานประจำ@สีลม Btsสุรศักดิ์', 5, 36, 0, 24, 50, 1, 1, 2, 0, '', 60000, '10.00-19.00', 'ประกันสังคม\r\nประกันกลุ่ม (สำหรับพนักงานประจำ)\r\nเบี้ยขยัน และเงินคอมมิชชั่น\r\nงานเลี้ยงสังสรรค์บริษัท\r\nCompany Trip\r\nชุด Uniform\r\nวันหยุดประจำปี\r\nBonus\r\nทริปท่องเที่ยวภายในประเทศ', '- Develop structures and standards for the use and maintenance of the database management system.\r\n- Monitor the performance of the database management system and restructure as necessary.\r\n- Optimize database performance for query and data loading, including data partitioning, indexing, etc.\r\n- Control access permissions and privileges for the database.\r\n- Implement and document backup and restore processes in a standard format.\r\n- Implement maintenance and security procedures for the database, including adding and removing users and managing quotas.\r\n- Operate databases on Windows and Linux operating systems.\r\n- Perform data replication and Migration for MS SQL, and MongoDB.\r\n- Configure and use SSMS, SSIS, SSRS, and SSAS in online and data warehouse environments.\r\n- Provide disaster recovery services for databases.\r\n- Troubleshoot application and database-related issues.\r\n- Conduct general technical troubleshooting and provide consultation to the application team.\r\n- Test database recovery for existing services to support SLA.\r\n- Perform regular system audits to optimize performance.\r\n- Document system configurations, processes, and procedures.', 'บริษัท จัดหางาน ลีดดิ้งพาวเวอร์ จำกัด\r\nLeading Power Recruitment Co., Ltd. 138/92 Jewellery Center Building 24th FL, Unit 24C2, Naret Rd., Siphraya, Bangrak, Bangkok 10500\r\nโทรศัพท์ : 095-208-3522', '1', 'เขตบางรัก', 'j32.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 3),
(13, 'IT COMPANY', 'Programmer (โปรแกรมเมอร์)', 5, 36, 1, 22, 30, 1, 1, 4, 0, '', 0, '9.00-17.00', '-เงินเดือน\r\n-เบี้ยเลี้ยง\r\n-ประกันสังคม\r\n-ประกันอุบัติเหตุ\r\n-โบนัสตามผลประกอบการ ฯลฯ', '- พัฒนาโปรแกรมในรูปแบบ Windows หรือ Web Application ด้วย ASP.net, Mobile Applications, React, APIs พร้อมฐานข้อมูล MS SQL Server, MySQL ตามที่ได้รับมอบหมาย\r\n- Arduino concepts\r\n- OOPs concept', 'บริษัท เป็นหนึ่ง โฮลดิ้ง จำกัด (PenNueng Holding Co., Ltd.)\r\n576/18 ถ,ลาดพร้าว 112\r\nแขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310\r\nโทรศัพท์ : 093-624-5544, 093-654-5544', '1', 'เขตวังทองหลาง', 'j33.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 4),
(14, 'IT COMPANY', 'โปรแกรมเมอร์ (บริษัทในเครือ) สามารถเริ่มงานได้ทันที', 5, 36, 0, 28, 50, 4, 1, 2, 0, '', 0, '8.30-18.00', '1. เงินเดือนประจำ\r\n2. ค่าล่วงเวลา\r\n3. โบนัสที่จูงใจ (ตามผลการประเมินรายบุคคล)\r\n4. กองทุนสำรองเลี้ยงชีพ\r\n5. ค่ารักษาพยาบาลผู้ป่วยนอก-ผู้ป่วยใน ( ตามตำแหน่งงาน )\r\n6. เงินได้ค่าเที่ยววิ่ง (ตามตำแหน่งงาน)\r\n7. เงินได้ค่าลงสินค้า (ตามตำแหน่งงาน)\r\n8. เงินได้สวัสดิการวันเกิด\r\n9. ค่าน้ำมัน/ค่าโทรศัพท์ (ตามตำแหน่งงาน)\r\n10. ตรวจสุขภาพประจำปี\r\n11. เงินกู้สวัสดิการบริษัทฯ\r\n12. สวัสดิการกู้ซื้อบ้านดอกเบี้ยอัตราพิเศษกับธนาคารอาคารสงเคราะห์\r\n13. เงินช่วยเหลือการจัดงานพิธีสมรส/อุปสมบท/กรณีถึงแก่กรรม\r\n14. สวัสดิการกระเช้าเยี่ยมไข้\r\n15. ชุดยูนิฟอร์มฟรีทุกปี (ตามตำแหน่งงาน)\r\n16. ค่าพาหนะ/ค่าเบี้ยเลี้ยงในการเดินทางในประเทศ/ต่างประเทศ\r\n17. ประกันอุบัติเหตุกลุ่ม(ตามตำแหน่งงาน)\r\n18. ทุนการศึกษาบุตรพนักงาน\r\n19. เงินรางวัลอายุงาน\r\n20. ประกันสังคมและกองทุนเงินทดแทน\r\n21. ลาพักร้อนสูงสุด 14 วัน/ปี\r\n22. สิทธิ์การลากิจเริ่มที่ 6 วัน/ปี\r\n23. สิทธิในการลาอุปสมบท/ลาสมรส\r\n24. ส่วนลดสมาชิกสำหรับการออกกำลังกายที่ Fitness First และ Jetts Fitness', '•เขียนโปรแกรมตามความต้องการของผู้ใช้งาน\r\n•เขียนโปรแกรมเชื่อมต่อกับซอฟแวร์ภายในองค์กร\r\n•ทำงานร่วมกับคีย์ยูสเซอร์, PM และ key user ในการพัฒนาระบบให้สำเร็จ\r\nวัน-เวลาปฏิบัติงาน 08.30-18.00 น. หยุดเสาร์ -อาทิตย์', 'บริษัท ซี.เอ.เอส. เปเปอร์ จำกัด ถ.จันทร์43\r\nแขวงทุ่งวัดดอน เขตสาทร กรุงเทพมหานคร', '1', 'เขตสาทร', 'j34.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 5),
(15, 'IT COMPANY', 'IT Technical Support', 5, 36, 0, 24, 33, 0, 1, 2, 0, '', 37000, '9.00-18.00', '-เงินดือน + เบี้ยเลี้ยง + ประกันอุบัติเหตุ + commission และ Incentive ฯลฯ\r\n-ประกันสังคม\r\n-ค่าเล่าเรียนบุตร\r\n-ฝึกอบรมที่ประเทศเกาหลี', '• เข้าสำรวจพื้นที่หน้างาน เพื่อติดตั้งเครื่องมือแพทย์และระบบให้เป็นไปตามมาตราฐาน\r\n• เข้าแก้ไขอุปกรณ์ หรือทำการ maintenance ตามรอบ ให้เป็นไปตามข้อกำหนด\r\n• ตรวจสอบอุปกรณ์ที่ติดตั้งให้อยู้ในสภาพพร้อมใช้งาน\r\n• ทำรายงานการแก้ไขอุปกรณ์ และการส่งมอบงาน\r\n• ทำการ Remote แก้ไขหากมีการขอความช่วยเหลือ\r\n• งานอื่นๆที่ได้รับมอบหมาย', 'บริษัท ไฟน์ เมด จำกัด\r\n17/17-18 ซอยวิภาวดีภาวดีรังสิต 58 แยก 2\r\nแขวงตลาดบางเขน เขตหลักสี่ กรุงเทพมหานคร 10210\r\nโทรศัพท์ : 02-561-1045', '1', 'เขตหลักสี่', 'j35.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 6),
(16, 'IT COMPANY', 'IT Development Section Head (Programmer)', 5, 36, 0, 23, 50, 0, 1, 1, 0, '', 0, '9.30-17.00', '- ค่าครองชีพทุกตำแหน่ง (หลังผ่านทดลองงาน 119 วัน)\r\n- เบิ้ยขยัน (ตามเงื่อนไขของบริษัทฯ)\r\n- ประกันชีวิตและบัตรประกันสุขภาพกลุ่ม OPD/IPD\r\n- ประกันสังคม กองทุนเงินทดแทน\r\n- ตรวจสุขภาพประจำปี\r\n- ตรวจสุขภาพก่อนเริ่มงาน\r\n- โบนัส (ตามเงื่อนไขบริษัทฯ)\r\n- ปรับเงินเดือนประจำปี (ตามคะแนน KPI เงื่อนไขของบริษัทฯ)\r\n- ลาป่วย ลากิจ ลาพักร้อน ลาอื่นๆ (ตามกฎหมายเเรงงาน)\r\n- วันหยุดประจำปี วันลาพักร้อนประจำปี (ตามกฎหมายเเรงงาน)\r\n- อาหาร เช้า/กลางวัน (ราคาพนักงาน)\r\n- ห้องพยาบาล (พยาบาลวิชาชีพประจำดูแล)\r\n- รถรับ - ส่งพนักงาน (วิ่งตามเส้นทางที่บริษัทฯ กำหนด)\r\n- ชุดยูนิฟอร์มพนักงาน\r\n- เงินช่วยเหลือฌาปนกิจ (บิดา/มารดา)\r\n- เงินบำเหน็จ หลังเกษียณอายุ (ตามกฎหมายกำหนด)', '1. เก็บข้อมูลความต้องการของผู้ใช้งานเพื่อออกแบบและพัฒนาระบบ โดยจะต้องมีการทำ UAT และเอกสารทางด้านเทคนิคและคู่มือ\r\n2. การวางแผนและออกแบบโครงสร้างของแอปพลิเคชัน ที่พัฒนาขึ้น\r\n3. Customize ฟอร์มและรายงานของ Epicor ERP ตามที่ได้รับอนุมัติให้จัดทำ\r\n4. ปรับปรุง ซอฟต์แวร์ หรือ แอปพลิเคชัน ให้คงไว้ซึ่งประสิทธิภาพ\r\n5. การติดต่อและประสานงานกับทีมอื่นๆ เพื่อสร้างซอฟต์แวร์ หรือแอปพลิเคชันที่มีประสิทธิภาพ', 'บริษัท พิบูลย์ชัยน้ำพริกเผาไทยแม่ประนอม จำกัด\r\nหมู่ที่ 12 68/10 บรมราชชนนี ถ. บรมราชชนนี แขวงศาลาธรรมสพน์ เขตทวีวัฒนา กรุงเทพมหานคร 10170\r\nโทรศัพท์ : 02-441-3595 ต่อ 0', '1', 'เขตทวีวัฒนา', 'j36.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 5),
(17, 'IT COMPANY', 'Supervisor - Infrastructure & IT Risk management', 5, 36, 0, 23, 50, 3, 1, 2, 0, '', 0, '8.30-17.00', 'Our standard fringe benefit including:\r\nAnnual Bonus\r\nHealth/Medical Checkup\r\nMedical Expense\r\nLife Insurance\r\nAccident Insurance\r\nUniform\r\nMeal allowance\r\nProvident Fund\r\nSocial Security\r\nWorkmen Compensation Fund\r\nTraditional Holiday\r\nVacation/ Annual Leave\r\nOvertime & special allowance', '-Perform long-term strategic planning in information security for areas of computer security, IT infrastructure, network, application support, recovery procedure, disaster recovery procedure, general computer compliance, law compliance, and IT risk.\r\n-Responsible for day-to-day security operations issues, assist in audits and track issues until closer, implement and ensure compliance of IT security policies and processes, Manage and work with platform, system service provider, relevant section, system owner to close the deviations and noncompliance issues.\r\n-Report to management in area of information security including security outbreak, patch management, intrusion activities, log analyzer, IT risk result, compliance, system health to prevent the interruption and supporting action plan development.\r\n-Safeguard the information system assets by identifying and solving potential and actual security problems, Protect the system by defining access privileges, control structures and instruction and resources, implements security improvements by assessing current situation, evaluating trends, anticipating requirements improve the security of whole IT systems.\r\n-Conduct ongoing information security education to user in order to improve computer skill and customers satisfaction.', '64 M.1, Bangna-Trad Rd., Km.21,\r\nตำบลศีรษะจรเข้ใหญ่ อำเภอบางเสาธง จังหวัดสมุทรปราการ 10540', '2', 'บางเสาธง', 'j37.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 2),
(18, 'IT COMPANY', 'Programmer / โปรแกรมเมอร์ (Senior)', 5, 36, 0, 22, 35, 3, 1, 1, 0, '', 45000, '9.00-17.00', '1. ประกันสุขภาพกลุ่ม คุ้มครองทุกกรณี (IPD / OPD / อุบัติเหตุ)\r\n2. ประกันสังคม\r\n3. โบนัสและปรับเงินเดือนประจำปี\r\n4. เบี้ยขยันรายเดือน (เฉพาะตำแหน่ง)\r\n5. Commission และ Incentive (เฉพาะตำแหน่ง)\r\n6. ฟรีอาหารกลางวัน\r\n7. เสื้อฟอร์มพนักงานประจำ\r\n8. วันลาป่วย ลากิจ ลาพักร้อน\r\n9. วันหยุดประจำปี 13 วันต่อปี\r\n10. กิจกรรมเสริมสร้างความสัมพันธ์ประจำปี (ท่องเที่ยวประจำปี/ งานเลี้ยงประจำปี )', '-พัฒนาเว็บไซต์ทั้ง Frontend และ Backend เพื่อตอบสนองความต้องการใหม่ๆ ของบริษัท\r\n-พัฒนาระบบ Web Application และ API\r\n-แก้ไขปัญหาการใช้งานของโปรแกรมตามที่ลูกค้าร้องขอได้', 'บริษัท โซโกะจัน จำกัด\r\n888 หมู่ที่ 5 ตึกวีจีอาร์ ชั้น 4 ถนนศรีนครินทร์\r\nตำบลสำโรงเหนือ อำเภอเมืองสมุทรปราการ จังหวัดสมุทรปราการ 10270\r\nโทรศัพท์ : 065-389-4569', '2', 'เมืองสมุทรปราการ', 'j38.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 3),
(19, 'IT COMPANY', 'PM,SA, Sr.(Full Stack developer,Front end,Back end),Auto mate Tester', 5, 36, 0, 25, 40, 2, 1, 1, 0, '', 50000, '10.00-18.00', '-โบนัส,ปรับเงินเดือนประจำปี\r\n-ประกันสังคม\r\n-ค่าอาหาร 88 บาท\r\n-ชุดฟอร์มพนักงาน3ชุดต่อปี\r\n-เงินช่วยเหลือมงคลสมรส/งานฌาปณกิจ\r\n-วันหยุดตามประเพณีและวันหยุดพักผ่อนประจำปี', 'Mobile(Flutter)\r\nBack end (Node js)(golang)\r\nWeb\r\nSuper App Platform\r\n(HYBRID WORK)', 'X-ONE(Thailand) Co., Ltd.\r\n149/7 Surawongse Rd\r\nแขวงสุริยวงศ์ เขตบางรัก กรุงเทพมหานคร 10500\r\nโทรศัพท์ : 02-166-8888 (auto line)', '1', 'เขตบางรัก', 'j39.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 2),
(20, 'IT COMPANY', 'IT Service Desk Staff (JLPT N2up) T02850', 5, 36, 0, 25, 35, 3, 1, 1, 0, '', 63000, '9.00-17.00', '-', '- Support global customers regarding incident, inquiry and request issues.\r\n- Coordinate and be the contact point for global support teams in order to fulfill customer requirement.\r\n- Escalate and follow up issues/projects with all related parties.\r\n- Create, follow up, close ticket in system.\r\n- English to Japanese translation and vice versa will be required in the operation from time to time.\r\n- Perform tasks as assigned by supervisors.\r\n- Be able to work in shift rotation, weekend and nightshift.', 'CareerLink Recruitment (Thailand) Co., Ltd.\r\n47, Room 58S, Sukhumvit 69 Rd.\r\nแขวงพระโขนงเหนือ เขตวัฒนา กรุงเทพมหานคร 10110\r\nโทรศัพท์ : 02-019-2962', '1', 'เขตวัฒนา', 'j40.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 7),
(21, 'IT COMPANY', 'Software Developer [Part-time]', 5, 36, 0, 20, 25, 0, 2, 2, 0, '', 0, '9.00-18.00', 'วันเวลาทำงาน: จันทร์ – ศุกร์ 9:00-18:00 (หยุดเสาร์-อาทิตย์) อย่างน้อยสัปดาห์ละ 3 วัน (ขึ้นอยู่กับการตกลงกับทีม)\r\nค่าตอบแทน คิดเป็นรายชั่วโมงตามเวลาทำงานจริง\r\nค่าเดินทางตามจริง (เดือนละไม่เกิน 2,000 บาท)\r\nกิจกรรมชมรม (บริษัทมีงบสนับสนุนให้)\r\nปาร์ตี้สังสรรค์ของบริษัททุกเดือน\r\nฟรี! Snack Bar ขนมและเครื่องดื่ม จัดเต็มไม่อั้น ลงใหม่ทุกสัปดาห์', 'ช่วยเหลืองานด้าน Programmer ในโปรเจคเว็บเซอร์วิสหรือแอปพลิเคชันของบริษัท โดยใช้ภาษาและ engine ที่หลากหลาย\r\nงานด้าน Server, Network และงานที่เกี่ยวข้องกับ IT\r\nงานด้านอื่นๆ ที่น้องสนใจอยากเรียนรู้และปฎิบัติจริง', 'ห้วยขวาง, กรุงเทพมหานคร', '1', 'ห้วยขวาง', 'j41.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 7),
(22, 'IT COMPANY', 'Part-time เจ้าหน้าที่ธุรการคีย์ข้อมูล', 5, 36, 0, 18, 30, 0, 2, 3, 0, '', 13000, '8.00-17.00', 'เงินโบนัสตามผลงาน\r\nค่าทำงานล่วงเวลา\r\nประกันสังคม\r\nตามข้อตกลงของบริษัท\r\nวันพักร้อน สูงสุด 10 วัน\r\nเบี้ยขยันพิเศษประจำเดือน สูงสุด 600 บาท\r\nเบี้ยขยันประจำปี (ตามเงื่อนไขบริษัท)\r\nประกันอุบัติเหตุ\r\nตรวจสุขภาพประจำปี\r\nท่องเที่ยวประจำปี\r\nงานจัดเลี้ยงปีใหม่\r\nห้องซ้อมดนตรี\r\nกีฬา อาทิ โต๊ะพลู สนุกเกอร์ ตะกร้อ ฟุตบอล แบตมินตัน ปิงปอง\r\nงานกีฬาสี\r\nชุดยูนิฟอร์มบริษัทฟรี\r\n', 'หน้าที่ความรับผิดชอบ\r\n\r\n- งานด้านเอกสาร คีย์ข้อมูล ตรวจสอบเอกสาร\r\nใช้โปรแกรมคอมพิวเตอร์พื้นฐานได้ Microsoft Office\r\nหน้าที่อื่นๆ ที่ได้รับมอบหมาย', 'บริษัท ทีทีพี (ประเทศไทย) จำกัด\r\n261/1 หมู่ 2 ตำบลอ้อมน้อย อำเภอกระทุ่มแบน จังหวัดสมุทรสาคร 74130 ประเทศไทย', '59', 'กระทุ่มแบน ', 'j42.jpg', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิทยาการคอมพิวเตอร์และเทคโนโลยีสารสนเทศ', 'approved', 'pubpuang1811@gmail.com', 8),
(23, 'U-plus education', 'Graphic Designer', 5, 31, 2, 23, 27, 0, 1, 1, 0, '', 25000, '9.00-17.00', '-', '- คิด และสร้างสรรค์งานกราฟฟิคสำหรับโซเชียลมีเดียใน Platform ต่างๆ\r\n- ออกแบบ คิดงาน concept ใหม่ๆ\r\n- ออกแบบสื่อสิ่งพิมพ์ต่างๆสำหรับใช้ภายในและนอกองค์กร', 'บริษัท ยูพลัส เอ็ดดูเคชั่น จำกัด\r\n29/1 ซ.ลาดพร้าว88\r\nแขวงพลับพลา เขตวังทองหลาง กรุงเทพมหานคร 10310\r\nโทรศัพท์ : 062-616-4677', '1', 'เขตวังทองหลาง', '', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'ศิลปกรรมและการออกแบบ', 'approved', 'pubpuang1811@gmail.com\r\n', 1),
(24, 'บริษัท บิ๊กสตาร์ จำกัด', 'หัวหน้าออกแบบผลิตภัณฑ์รองเท้า', 5, 30, 2, 25, 40, 5, 1, 2, 1, '', 0, '9.00-18-00', '1.ประกันสังคม\r\n2.ชุดยูนิฟอร์ม\r\n3.ตรวจสุขภาพประจำปี\r\n4.เงินโบนัสประจำปีตามผลประกอบการ\r\n5.ปรับเงินประจำปีตามผลงาน\r\n6.เกษียณอายุ 60 ปี\r\n7.กองทุนฌาปนกิจ\r\n8.สินค้าราคาพิเศษเฉพาะพนักงาน\r\n9.โรงอาหารและอาหารราคาพนักงาน\r\n\r\nสวัสดิการเฉพาะบางตำแหน่ง\r\n1.เบี้ยขยัน\r\n2.ประกันชีวิตกลุ่ม\r\n3.ค่ายานพาหนะ\r\n4.ค่าที่พัก\r\n5.ค่าคอมมิชชั่น', '1.ศึกษา Trend เรียบเรียงและรวบรวมการออกแบบรองเท้านำเสนอคณะผู้บริหารและผู้ที่เกี่ยวข้อง เพื่อสรุปแนวทางการออกแบบประจำปีและประจำไตรมาส\r\n2.จัดทำ Master Plan และ Action Plan การออกแบบรองเท้า นำเสนอต่อที่ประชุมตามกำหนดการต่างๆ\r\n3.บริหารจัดการและควบคุมดูแลฝ่ายพัฒนาผลิตภัณฑ์ให้เป็นไปตามเป้าหมายที่กำหนด\r\n4.ควบคุมดูแลการออกแบบผลิตภัณฑ์ให้ได้กำหนดด้านต่างๆ เช่น มาตรฐาน, เวลา, และเป็นไปตามแผนดำเนินการที่ตกลงกับฝ่ายต่างๆ\r\n5.ตรวจเช็ค, อนุมัติ ข้อมูลที่นำมากำหนด SPEC การผลิต และร่วมวิเคราะห์การผลิตร่วมกับฝ่ายผลิต\r\n6.ให้คำแนะนำวิธีการทำงานและเทคนิคการออกแบบต่างๆ กับทีมงาน\r\n7.ควบคุมดูแลพนักงานให้ปฏิบัติตามกฏระเบียบรวมถึงนโยบาย ความปลอดภัย และระบบมาตรฐานต่างๆ\r\n8.งานอื่นๆ ที่ได้รับมอบหมาย', 'บริษัท บิ๊กสตาร์ จำกัด\r\nเลขที่ 15 ซอยพระรามที่ 2 ซอย 100 ถนนพระราม 2\r\nแขวงแสมดำ เขตบางขุนเทียน กรุงเทพมหานคร 10150\r\nโทรศัพท์ : 02-451-3079', '1', 'เขตบางขุนเทียน', '', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'ศิลปกรรมและการออกแบบ', 'approved', 'pubpuang1811@gmail.com', 2),
(26, 'บริษัท แอสเสทโกรว เทรดดิ้ง', 'Office Engineer', 5, 13, 0, 30, 45, 5, 1, 2, 0, '', 0, '9.00-17.00', 'สวัสดิการ\r\nเงินเดือน + OT\r\nประกันสังคม\r\nยูนิฟอร์ม\r\nทำงาน 6วัน ต่อสัปดาห์', 'ดูแลงานก่อสร้างอาคาร ประจำโครงการ จังหวัดเชียงใหม่', 'บริษัท แอสเสทโกรว เทรดดิ้ง แอนด์ คอนสตรัคชั่น จำกัด\r\n89/36 หมู่ 6\r\nตำบลคลองข่อย อำเภอปากเกร็ด จังหวัดนนทบุรี 11120\r\nโทรศัพท์ : 081-622-2755\r\n', '3', 'ปากเกร็ด', '', '2024-11-11 00:42:32', '2025-07-23 22:45:12', 'วิศวกรรมศาสตร์', 'approved', 'pubpuang1811@gmail.com\r\n', 2);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `application_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_date` datetime DEFAULT current_timestamp(),
  `Suitability` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`application_id`, `account_id`, `job_id`, `application_date`, `Suitability`) VALUES
(1, 1, 24, '2025-07-19 18:47:10', '0');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(5) NOT NULL,
  `code` varchar(2) NOT NULL,
  `name_th` varchar(150) NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `geography_id` int(5) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `code`, `name_th`, `name_en`, `geography_id`) VALUES
(1, '10', 'กรุงเทพมหานคร', 'Bangkok', 2),
(2, '11', 'สมุทรปราการ', 'Samut Prakan', 2),
(3, '12', 'นนทบุรี', 'Nonthaburi', 2),
(4, '13', 'ปทุมธานี', 'Pathum Thani', 2),
(5, '14', 'พระนครศรีอยุธยา', 'Phra Nakhon Si Ayutthaya', 2),
(6, '15', 'อ่างทอง', 'Ang Thong', 2),
(7, '16', 'ลพบุรี', 'Loburi', 2),
(8, '17', 'สิงห์บุรี', 'Sing Buri', 2),
(9, '18', 'ชัยนาท', 'Chai Nat', 2),
(10, '19', 'สระบุรี', 'Saraburi', 2),
(11, '20', 'ชลบุรี', 'Chon Buri', 5),
(12, '21', 'ระยอง', 'Rayong', 5),
(13, '22', 'จันทบุรี', 'Chanthaburi', 5),
(14, '23', 'ตราด', 'Trat', 5),
(15, '24', 'ฉะเชิงเทรา', 'Chachoengsao', 5),
(16, '25', 'ปราจีนบุรี', 'Prachin Buri', 5),
(17, '26', 'นครนายก', 'Nakhon Nayok', 2),
(18, '27', 'สระแก้ว', 'Sa Kaeo', 5),
(19, '30', 'นครราชสีมา', 'Nakhon Ratchasima', 3),
(20, '31', 'บุรีรัมย์', 'Buri Ram', 3),
(21, '32', 'สุรินทร์', 'Surin', 3),
(22, '33', 'ศรีสะเกษ', 'Si Sa Ket', 3),
(23, '34', 'อุบลราชธานี', 'Ubon Ratchathani', 3),
(24, '35', 'ยโสธร', 'Yasothon', 3),
(25, '36', 'ชัยภูมิ', 'Chaiyaphum', 3),
(26, '37', 'อำนาจเจริญ', 'Amnat Charoen', 3),
(27, '39', 'หนองบัวลำภู', 'Nong Bua Lam Phu', 3),
(28, '40', 'ขอนแก่น', 'Khon Kaen', 3),
(29, '41', 'อุดรธานี', 'Udon Thani', 3),
(30, '42', 'เลย', 'Loei', 3),
(31, '43', 'หนองคาย', 'Nong Khai', 3),
(32, '44', 'มหาสารคาม', 'Maha Sarakham', 3),
(33, '45', 'ร้อยเอ็ด', 'Roi Et', 3),
(34, '46', 'กาฬสินธุ์', 'Kalasin', 3),
(35, '47', 'สกลนคร', 'Sakon Nakhon', 3),
(36, '48', 'นครพนม', 'Nakhon Phanom', 3),
(37, '49', 'มุกดาหาร', 'Mukdahan', 3),
(38, '50', 'เชียงใหม่', 'Chiang Mai', 1),
(39, '51', 'ลำพูน', 'Lamphun', 1),
(40, '52', 'ลำปาง', 'Lampang', 1),
(41, '53', 'อุตรดิตถ์', 'Uttaradit', 1),
(42, '54', 'แพร่', 'Phrae', 1),
(43, '55', 'น่าน', 'Nan', 1),
(44, '56', 'พะเยา', 'Phayao', 1),
(45, '57', 'เชียงราย', 'Chiang Rai', 1),
(46, '58', 'แม่ฮ่องสอน', 'Mae Hong Son', 1),
(47, '60', 'นครสวรรค์', 'Nakhon Sawan', 2),
(48, '61', 'อุทัยธานี', 'Uthai Thani', 2),
(49, '62', 'กำแพงเพชร', 'Kamphaeng Phet', 2),
(50, '63', 'ตาก', 'Tak', 4),
(51, '64', 'สุโขทัย', 'Sukhothai', 2),
(52, '65', 'พิษณุโลก', 'Phitsanulok', 2),
(53, '66', 'พิจิตร', 'Phichit', 2),
(54, '67', 'เพชรบูรณ์', 'Phetchabun', 2),
(55, '70', 'ราชบุรี', 'Ratchaburi', 4),
(56, '71', 'กาญจนบุรี', 'Kanchanaburi', 4),
(57, '72', 'สุพรรณบุรี', 'Suphan Buri', 2),
(58, '73', 'นครปฐม', 'Nakhon Pathom', 2),
(59, '74', 'สมุทรสาคร', 'Samut Sakhon', 2),
(60, '75', 'สมุทรสงคราม', 'Samut Songkhram', 2),
(61, '76', 'เพชรบุรี', 'Phetchaburi', 4),
(62, '77', 'ประจวบคีรีขันธ์', 'Prachuap Khiri Khan', 4),
(63, '80', 'นครศรีธรรมราช', 'Nakhon Si Thammarat', 6),
(64, '81', 'กระบี่', 'Krabi', 6),
(65, '82', 'พังงา', 'Phangnga', 6),
(66, '83', 'ภูเก็ต', 'Phuket', 6),
(67, '84', 'สุราษฎร์ธานี', 'Surat Thani', 6),
(68, '85', 'ระนอง', 'Ranong', 6),
(69, '86', 'ชุมพร', 'Chumphon', 6),
(70, '90', 'สงขลา', 'Songkhla', 6),
(71, '91', 'สตูล', 'Satun', 6),
(72, '92', 'ตรัง', 'Trang', 6),
(73, '93', 'พัทลุง', 'Phatthalung', 6),
(74, '94', 'ปัตตานี', 'Pattani', 6),
(75, '95', 'ยะลา', 'Yala', 6),
(76, '96', 'นราธิวาส', 'Narathiwat', 6),
(77, '97', 'บึงกาฬ', 'buogkan', 3);

-- --------------------------------------------------------

--
-- Table structure for table `recruiter`
--

CREATE TABLE `recruiter` (
  `recruiter_id` int(11) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `website` varchar(250) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `job_description` text DEFAULT NULL,
  `required_skills` varchar(250) DEFAULT NULL,
  `experience_required` int(11) DEFAULT NULL,
  `education_required` varchar(250) DEFAULT NULL,
  `salary_range` varchar(50) DEFAULT NULL,
  `location_company` varchar(100) DEFAULT NULL,
  `closing_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pdf`
--

CREATE TABLE `tbl_pdf` (
  `no` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `doc_name` varchar(200) NOT NULL COMMENT 'ชื่อเอกสาร',
  `doc_file` varchar(100) NOT NULL COMMENT 'ชื่อไฟล์เอกสาร',
  `dateCreate` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'วันที่เพิ่มเอกสาร'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_account`
--

CREATE TABLE `users_account` (
  `account_id` int(11) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `account_email` varchar(100) DEFAULT NULL,
  `account_realpassword` text DEFAULT NULL,
  `account_password` text DEFAULT NULL,
  `account_salt` varchar(250) DEFAULT NULL,
  `account_role` varchar(10) DEFAULT NULL,
  `account_images` varchar(100) DEFAULT NULL,
  `account_countlogin` int(1) DEFAULT NULL,
  `account_lock` int(1) DEFAULT NULL,
  `account_ban` datetime DEFAULT NULL,
  `oauth_id` varchar(250) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `token` varchar(250) DEFAULT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `addresses` varchar(255) DEFAULT NULL,
  `phone_numbers` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_account`
--

INSERT INTO `users_account` (`account_id`, `google_id`, `account_name`, `first_name`, `last_name`, `account_email`, `account_realpassword`, `account_password`, `account_salt`, `account_role`, `account_images`, `account_countlogin`, `account_lock`, `account_ban`, `oauth_id`, `last_login`, `created_at`, `token`, `birthday`, `gender`, `addresses`, `phone_numbers`) VALUES
(1, '', 'boss', NULL, NULL, 'bossapr2001@gmail.com', '123', '$argon2id$v=19$m=65536,t=4,p=1$WE84Y1BIVkdySHRseHIuQQ$CA1GnDd6zm8Exvt4NEGoFkxj7OleQ9AFMCwz7PoxmhA', '7e175484788232e4ea32', 'user', 'default_images_account.jpg', 0, 0, '2025-07-19 19:00:05', '', '2025-07-19 14:13:29', '2025-07-19 12:13:29', '981a99102eb7546cf07d9e8543feaccfea72d1054b87d565f0308942ad3f33d5', 'N/A', 'N/A', 'N/A', 'N/A'),
(2, '', 'boss1', NULL, NULL, 'bossapr2002@gmail.com', '123', '$argon2id$v=19$m=65536,t=4,p=1$OEVKOVV6bnk1YjBEb0ZCRQ$3BLt9admkI8YHTGWIUL0zWDB2FDjQSuXdxG5Vj23kXU', 'f5a64e9fd6255f8196b174dd4178c78d9dc67b92ab9a9bdb905b77b130f3826d6da407d70524c6700655396fff29975096c0e8ecf8977b3e39237e09299d052487e50665e9cbb1010668da', 'user', 'default_images_account.jpg', 0, 0, NULL, '', '2025-07-19 14:24:59', '2025-07-19 12:24:59', '7aa784ba79af4fb3238015f919d8c0f323649bc4f5711a9d6d56dfb70d31f5d4', 'N/A', 'N/A', 'N/A', 'N/A'),
(3, '', '1', NULL, NULL, '1', '1', '$argon2id$v=19$m=65536,t=4,p=1$cG5CcFhlbVk4dTgwbHpUZg$WHiEF5aiBMM+tOR/xVV/PtCFR5O9R9F7wlIUHeguzgY', '2b135a98c604a7877483f57fdfa2c83d757da1c7a3bc37c5a8aa8476a363197cb7f1790ca850', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:53:29', 'f7b6fd0f6b28ea51a3c6f0699d15321aced9e8484cc5cde964a62f11494d8382', 'N/A', 'N/A', 'N/A', 'N/A'),
(4, '', '2', NULL, NULL, '2', '2', '$argon2id$v=19$m=65536,t=4,p=1$dGlpaUZGSTU3WDJ0ckdJdw$xK0/IqiZsic+eaNGJVEr9KVbuadSM8xsh1J9MpKTNVY', '0e2be93bec20c2fd63ac42c0ef29844d52f57179dd817b6f4123419110dc19a1eaa34f477e0c414a92169911c1e115eecae3d91bc9d8d92a5b49dfd63b764483059cdbadb0e1190f47bb69cdd3f05706f9065d846fb2a465', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:53:36', '02d50ddd01cedd90718befa7be6a429a715a72128f261277f7f22c070138e5fc', 'N/A', 'N/A', 'N/A', 'N/A'),
(5, '', '3', NULL, NULL, '3', '3', '$argon2id$v=19$m=65536,t=4,p=1$VS9TdS9UeUFrV0xYaXlUNA$LGZF/h6xK5/J0Lw2tcGiRTIxYTYxTqpvB56tY4rxNjo', '18a1d1e5754d52cbfcaaa5376ced4f7c28b744afabb3f0564c096d8db2a613cdb6a6394e63be0824ba8be94a27e3386cb225773d7ae5171f0a3dc48e97085f6c9919308d8d532bf7ed174e9b2c6ed0e856059a82089b4dab66071df381b5f36f1a625ebf2ed073c51e3b0b6ec15c6fc4c832', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:53:42', 'c8ceb1df3c37e2a6eeb0c467d03ce95aa74ffefd41881677f44748a4c2928154', 'N/A', 'N/A', 'N/A', 'N/A'),
(6, '', '4', NULL, NULL, '4', '4', '$argon2id$v=19$m=65536,t=4,p=1$bzFTVko0ZGIueVhxcTlkTQ$Gp6EBmsE+TxUysA5ssh+jm+GbBz+d8UF9Vmc5URIQZs', '28795214b2f2634d370742f1dc150a0f26b4fe79a3c714a05b96071ce410c236a41ed6ada3a2d1fa0e24bf1d75f4ff1e9ba652f244096522de83734bc08e2894e4ce', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:53:48', '28aa0286448d1c366e12a8f305ee2246654d656f09a7b5bec56b4d9aa45b621f', 'N/A', 'N/A', 'N/A', 'N/A'),
(7, '', '5', NULL, NULL, '5', '5', '$argon2id$v=19$m=65536,t=4,p=1$am1EMUZDN1YxM0phSFFieQ$PkCmT8PdETXTNGt8Z9yaliLOQR+nyvgHVEHw5kQb2Yw', 'faace9911812dfcfee0e0c28ad65619ab1387c191665253a60e4d251', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:53:54', 'f2766658fcb9e06435011ff0ea26540a6acb3d3f63798b4ec29fd6d822bc295f', 'N/A', 'N/A', 'N/A', 'N/A'),
(8, '', '6', NULL, NULL, '6', '6', '$argon2id$v=19$m=65536,t=4,p=1$WTZrU09yRFlYSWxmWW1wUA$o3M8nli42A9fURAJWihzKvJbtgFGQrEVdB+2WNV//zY', 'd1c0868aef3bcc41de8e31fd2902855b44c53848eb5aff87c57a4d0048c27e68afd2ffb30bec1401011c75900d209e3a052f2f74896ee67afb4774f21acda931f0ec30632e', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:54:00', 'be2a1ffd95ef8b35ae5f5cfe91fe10461f8a1c847ec921defc432bef5d8de339', 'N/A', 'N/A', 'N/A', 'N/A'),
(9, '', '7', NULL, NULL, '7', '7', '$argon2id$v=19$m=65536,t=4,p=1$OFRyU0VGUmtCMGNzZVVFTg$P7bYFEMD919G4oHLAF8WceblXO58/SbnhH2U14mCRuo', '269621fc5c', 'user', 'default_images_account.jpg', 0, 0, NULL, '', NULL, '2025-07-19 11:54:06', '14b664c1ef0ae4cdd0470bf3b6310c82ed5dc56e87b7f9a8b2658e9c5dc1b126', 'N/A', 'N/A', 'N/A', 'N/A'),
(10, '', 'BossAPR', NULL, NULL, 'srisamut_a@silpakorn.edu', '1234', '$argon2id$v=19$m=65536,t=4,p=1$dXUzekcyYmZ3b3duN25OaA$GbE9/MCaXjvfufItOEYMZcpY+Beg1X6CYViO0pKpBeo', '92b50d47e91a6c38d6954a18d693d4e612ee500ff4c6180f99909aea8482897feb07bd5249895af88e8a9ce6a52e', 'user', 'default_images_account.jpg', 0, 0, NULL, '', '2025-07-19 14:19:51', '2025-07-19 12:19:51', '860ff15da564b11fc3014ace655b5df51b041a3bc47f27b6daa0cdbb89276456', 'N/A', 'N/A', 'N/A', 'N/A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`applicant_id`),
  ADD KEY `fk_applicant_user` (`account_id`);

--
-- Indexes for table `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_ad`
--
ALTER TABLE `job_ad`
  ADD PRIMARY KEY (`job_ad_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recruiter`
--
ALTER TABLE `recruiter`
  ADD PRIMARY KEY (`recruiter_id`);

--
-- Indexes for table `tbl_pdf`
--
ALTER TABLE `tbl_pdf`
  ADD PRIMARY KEY (`no`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `users_account`
--
ALTER TABLE `users_account`
  ADD PRIMARY KEY (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_ad`
--
ALTER TABLE `job_ad`
  MODIFY `job_ad_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `recruiter`
--
ALTER TABLE `recruiter`
  MODIFY `recruiter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_pdf`
--
ALTER TABLE `tbl_pdf`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_account`
--
ALTER TABLE `users_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applicant`
--
ALTER TABLE `applicant`
  ADD CONSTRAINT `applicant_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`),
  ADD CONSTRAINT `fk_applicant_user` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`);

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`),
  ADD CONSTRAINT `job_applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job_ad` (`job_ad_id`);

--
-- Constraints for table `tbl_pdf`
--
ALTER TABLE `tbl_pdf`
  ADD CONSTRAINT `tbl_pdf_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`),
  ADD CONSTRAINT `tbl_pdf_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`),
  ADD CONSTRAINT `tbl_pdf_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `users_account` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
