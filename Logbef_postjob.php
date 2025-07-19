<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--========== BOX ICONS ==========-->
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>

    <!--========== CSS ==========-->
    <link rel="stylesheet" href="assets/css/styles (1).css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap"
        rel="stylesheet">
    <title>website job</title>

    <style>
    /* Form container styles */
    .form-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        /* ลดระยะห่างระหว่างช่อง */
        align-items: center;
    }

    .left-container,
    .right-container {
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        min-height: 300px;
    }

    .left-container h2,
    .right-container h2 {
        margin-bottom: 5px;
    }

    .left-container p,
    .right-container p {
        margin-top: 0;
        line-height: 1.4;
    }

    .button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        display: inline-block;
        margin-top: 10px;
    }

    .button:hover {
        background-color: #45a049;
    }

    nav .nav__menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    nav .nav__menu .nav__list {
        display: flex;
        gap: 20px;
        margin: 0;
    }

    nav .nav__menu .btn {
        margin-left: 20px;
    }

    header {
        padding: 0;
        /* ลดการ padding ของ header */
        margin: 0;
        /* ลด margin ของ header */
    }

    /* ปรับขนาดความกว้าง header กับ main*/
    main.l-main {
        margin-top: -150px;
        /* ลดระยะห่างระหว่าง header กับ main */
    }

    /* ลดระยะห่างระหว่าง section ต่าง ๆ */
    .contact.section.bd-container {
        padding: 0;
        margin-top: 0;
    }

    /* ลดระยะห่างระหว่างข้อความ h1, h2 และ p */
    .home__container h2,
    .home__container h1,
    .home__container p {
        margin-bottom: 5px;
        margin-top: 1px;
    }

    .button {
        display: block;
        margin: 20px auto;
        text-align: center;
    }

    /* ปรับระยะห่างหัวข้อและเนื้อหาให้ชิดกัน */
    h2,
    h1 {
        margin-bottom: 5px;
    }

    p {
        margin-top: 5px;
    }

    /*========== Header Styles ==========*/
    .l-header {
        /*background-color: rgba(255, 255, 255, 0.9); /* สีขาวโปร่งใส */
        position: sticky;
        /* ติดอยู่ที่ด้านบนเมื่อเลื่อน */
        top: 0;
        /* เปลี่ยนค่าเป็น 0 เพื่อนำแถบ header ขึ้นสุด */
        z-index: 1000;
        /* ให้แสดงอยู่ข้างบนสุด */
        padding: 10px 0;
        /* ลด padding ด้านบนและล่างของ header */

        background-color: aliceblue;
    }
    </style>
</head>

<body>
    <!--========== HEADER ==========-->
    <header class="l-header" id="header">
        <nav class="nav bd-container">
            <img href="#" class="logo" src="assets/account_images/2.png">

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="HOME.php" class="nav__link">หน้าหลัก</a></li>
                    <li class="nav__item"><a href="about.html" class="nav__link">เกี่ยวกับเรา</a></li>
                    <!--li class="nav__item"><a href="#profile" class="nav__link">ข่าวสาร</a></li-->
                    <!--li class="nav__item"><a href="#article" class="nav__link">บทความ</a></li-->
                    <!--li class="nav__item"><a href="#profile" class="nav__link">รีวิว</a></li-->
                    <li class="nav__item"><a href="Contact_us.html" class="nav__link">ศูนย์ช่วยเหลือ</a></li>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="job_search.php"><button type="button" class="btn info"><b>บอร์ดหางาน</b></button></a>
                    <a href="Logbef_postjob.php"><button type="button"
                            class="btn success"><b>ประกาศหางาน</b></button></a>&nbsp;&nbsp;
                </ul>
            </div>

            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-menu'></i>
            </div>
        </nav>
    </header>

    <main class="l-main" style="margin-top: -120px;">
        <section class="contact section bd-container" id="contact">
            <div class="home__container bd-containerH">
                <h2 style="color: blue; text-align: center;">แนะนำบริการ สำหรับบริษัท</h2>
                <h1 style="font-size: 24px; text-align: center;">หาคนทำงาน กับ Job Search</h1>
                <h2 style="text-align: center;">Platform รับสมัครงาน/หาคนทำงาน ที่ หางานได้งาน หาคนได้คน ครบจบที่เดียว
                </h2>
                <p style="font-size: 14px; text-align: center;">ต้องการพนักงานประจำหรือพนักงานชั่วคราว ที่ดีมีคุณภาพ
                    เรามีฐานข้อมูลพิเศษ ของพนักงานที่คัดสรรแล้ว ไม่ว่าจะเป็นเด็กจบใหม่หรือคนมีประสบการณ์
                    โดยรวบรวมผู้สมัครจากทั่วประเทศ</p>
            </div>

            <div class="form-container">
                <div class="left-container">
                    <h2 style="color: blue; text-align: center;">Human resources Outsourcing services</h2>
                    <p style="font-size: 14px; text-align: center;">ให้คำปรึกษาและบริการ HR Outsource อย่างครบวงจร
                        บริการของเราถูกออกแบบมาเพื่อลดค่าใช้จ่ายให้แก่องค์กรของลูกค้า
                        ในด้านทรัพยากรบุคคลเพื่อให้ลูกค้าสามารถใช้เวลาในการพัฒนาธุรกิจได้อย่างเต็มศักยภาพ</p><br>
                    <a class="button" href="Contact_us.html">ติดต่อเรา</a>
                </div>

                <div class="right-container">
                    <h2 style="color: blue; text-align: center;">สมัครใช้งานกับเราวันนี้ ประกาศงานฟรีไม่จำกัด!</h2>
                    <p style="font-size: 14px; text-align: center;">
                        หาผู้สมัครได้รวดเร็วประหยัดเวลาสำหรับผู้ประกอบการที่ต้องมองหาคน</p><br><br>
                    <a class="button" href="form_login.php">เข้าสู่ระบบเพื่อประกาศงาน ฟรี!</a>
                </div>
            </div>
        </section>
    </main>

    <!--========== JOBS ==========-->
    <section class="menu section bd-container" id="menu">
        <span class="section-subtitle">Job Search</span>
        <h2 class="section-title" style="text-align: center;">ให้บริการครบวงจรแค่แจ้งความต้องการก็รอคนไปทำงานได้เลย</h2>

        <center>
            <div class="menu__container bd-grid" style="grid-template-columns: repeat(3, 210px);">
                <div class="menu__content">
                    <img src="assets/account_images/money.png" alt="" class="menu__img">
                    <h3 class="menu__name">ลดต้นทุน</h3><br>
                    <span class="menu__detail">ช่วยให้คุณประหยัดเวลาในการประกาศ ตามหาพนักงาน</span><br>
                </div>

                <div class="menu__content">
                    <img src="assets/account_images/callcenter.png" alt="" class="menu__img">
                    <h3 class="menu__name">งานราบรื่น</h3><br>
                    <span class="menu__detail">ธุรกิจหรืองานของคุณดำเนิน ไปได้ด้วยดีไม่ขาดคน</span><br>
                </div>

                <div class="menu__content">
                    <img src="assets/account_images/time.png" alt="" class="menu__img">
                    <h3 class="menu__name">ประหยัดเวลา</h3><br>
                    <span class="menu__detail">ลดต้นทุนและรายจ่ายในการจ้างงาน พนักงานประจำ</span><br>
                </div>
            </div>
        </center>
    </section>

    <!--========== FOOTER ==========-->
    <footer class="footer section bd-container">
        <div class="footer__container bd-grid">
            <div class="footer__content">
                <img href="#" class="logo" src="assets/account_images/2.png">
                <span class="footer__description">JOB SEARCH</span>
                <div>
                    <a href="#" class="footer__social"><i class='bx bxl-facebook'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-instagram'></i></a>
                    <a href="#" class="footer__social"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">บริการ</h3>
                <ul>
                    <li><a href="HOME.php" class="footer__link">หน้าแรก</a></li>
                    <li><a href="Logbef_postjob.php" class="footer__link">ประกาศหาพนักงาน</a></li>
                    <li><a href="job_search.php" class="footer__link">หางาน</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">ข้อมูล</h3>
                <ul>
                    <li><a href="Contact_us.html" class="footer__link">ติดต่อเรา</a></li>
                    <li><a href="about.html" class="footer__link">เกี่ยวกับเรา</a></li>
                </ul>
            </div>

            <div class="footer__content">
                <h3 class="footer__title">ที่อยู่</h3>
                <ul>
                    <li>กรุงเทพ ประเทศไทย</li>
                    <li>ถนนบรม 88</li>
                    <li>099 - 888 - 7777</li>
                    <li>@email.com</li>
                </ul>
            </div>
        </div>

        <p class="footer__copy">&#169; 2024 WEBJOB. All right reserved</p>
    </footer>

</body>

</html>