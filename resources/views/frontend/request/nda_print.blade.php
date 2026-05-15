<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>NDA - {{ $requestForm->request_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm 15mm;
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: local('TH Sarabun New'), local('THSarabunNew');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: local('TH Sarabun New Bold'), local('THSarabunNew-Bold');
        }



        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 18px;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page {
            position: relative;
            min-height: 257mm;
            box-sizing: border-box;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header {
            margin-bottom: 10px;
            margin-top: 10px;
            text-align: right;
        }

        .req-no {
            font-weight: bold;
            font-size: 16px;
        }

        .dot-line {
            display: inline-block;
            text-align: center;
            position: relative;
        }

        .dot-line::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 7px;
            border-bottom: 1px dotted #000;
        }

        .logo-text {
            font-family: Arial, sans-serif;
            font-size: 28px;
            font-weight: bold;
            color: #d31221;
            letter-spacing: -1px;
        }

        .doc-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 45px;
            margin-bottom: 20px;
        }

        .content {
            text-align: justify;
        }

        .indent {
            text-indent: 1.5cm;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            font-size: 14px;
            color: #999;
            border-top: 0.5pt solid #ddd;
            padding-top: 5px;
        }

        .footer-left {
            float: left;
        }

        .footer-right {
            float: right;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signature-cell {
            width: 50%;
            vertical-align: top;
            padding: 20px 10px;
            text-align: center;
        }

        .sig-area {
            position: relative;
            height: 65px;
            margin-bottom: -10px;
        }

        .sig-dots {
            position: absolute;
            bottom: 0;
            left: 10%;
            right: 10%;
            border-bottom: 1px dotted #000;
        }

        .sig-image {
            max-height: 65px;
            max-width: 200px;
            position: absolute;
            bottom: 0px;
            left: 50%;
            transform: translateX(-50%);
        }

        .sig-typed-name {
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'THSarabunNew', sans-serif;
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            white-space: nowrap;
        }

        .sig-label-wrapper {
            position: relative;
            display: inline-block;
        }


        .sig-label {
            font-size: 16px;
        }

        .sig-name {
            font-size: 16px;
            margin-top: 0px;
        }

        .sig-date {
            font-size: 14px;
            color: #666;
            margin-top: 3px;
        }

        .list-item {
            padding-left: 1.5cm;
            margin-bottom: 5px;
        }

        p {
            margin-bottom: 10px;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <!-- PAGE 1 -->
    <div class="page">
        <div class="header">
            <div class="logo-text">Kumwell</div>
        </div>

        <div style="text-align: center; font-weight: bold; font-size: 20px; margin-bottom: 20px; margin-top: 20px;">
            ข้อตกลงรักษาความลับ
        </div>

        <div class="content">
            <p class="indent" style="margin-top: 0;">
                ข้อตกลงฉบับนี้ทำขึ้น เมื่อวันที่
                {{ $existing->agreement_date ? $existing->agreement_date->day : now()->day }} เดือน
                {{ $months[$existing->agreement_date ? $existing->agreement_date->format('m') : now()->format('m')] ?? '' }}
                {{ ($existing->agreement_date ? $existing->agreement_date->year : now()->year) + 543 }}
                ณ บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) เลขที่ 358 ถนน เลี่ยงเมืองนนทบุรี ตำบลบางกระสอ
                อำเภอเมืองนนทบุรี จังหวัดนนทบุรี ระหว่าง
            </p>

            <p class="indent">
                <strong>บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)</strong>
                โดยคุณ{{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }} ตำแหน่ง
                {{ $manager->department_name ?? 'ผู้จัดการแผนกเทคโนโลยีสารสนเทศและการสื่อสาร' }} ตัวแทนผู้มีอำนาจลงนาม
                เลขที่ 358 ถนน เลี่ยงเมืองนนทบุรี ตำบลบางกระสอ อำเภอเมืองนนทบุรี จังหวัดนนทบุรี 11000
                ซึ่งต่อไปนี้ในข้อตกลงจะเรียกว่า <strong>"บริษัท"</strong> ฝ่ายหนึ่ง กับ
            </p>

            @php
                $nameParts = explode(' ', $existing->full_name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
            @endphp
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 5px;">
                <span
                    style="white-space: nowrap;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;({{ $existing->prefix }})</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $firstName }}</span>
                <span style="white-space: nowrap;">นามสกุล</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $lastName }}</span>
                <span style="white-space: nowrap;">อายุ</span>
                <span class="dot-line" style="width: 50px; margin: 0 3px;">{{ $existing->age }}</span>
                <span style="white-space: nowrap;">ปี</span>
            </div>
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 5px;">
                <span style="white-space: nowrap;">เลขประจำตัวประชาชน</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->id_card_no }}</span>
                <span style="white-space: nowrap;">บ้านเลขที่</span>
                <span class="dot-line" style="width: 60px; margin: 0 3px;">{{ $existing->address_no }}</span>
                <span style="white-space: nowrap;">ซอย</span>
                <span class="dot-line" style="width: 80px; margin: 0 3px;">{{ $existing->soi }}</span>
            </div>
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 5px;">
                <span style="white-space: nowrap;">ถนน</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->road }}</span>
                <span style="white-space: nowrap;">ตำบล</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->tambon }}</span>
                <span style="white-space: nowrap;">อำเภอ</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->amphoe }}</span>
            </div>
            <div style="display: flex; flex-wrap: wrap; margin-bottom: 5px;">
                <span style="white-space: nowrap;">จังหวัด</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->province }}</span>
                <span style="white-space: nowrap;">หมายเลขติดต่อ</span>
                <span class="dot-line" style="flex: 1; margin: 0 3px;">{{ $existing->contact_no }}</span>
            </div>
            <p>
                ปรากฏตามสำเนาบัตรประชาชนที่รับรองสำเนาถูกต้องซึ่งต่อไปนี้ในข้อตกลงนี้จะเรียกว่า
                <strong>"พนักงาน"</strong>
                อีกฝ่ายหนึ่ง
            </p>

            <p class="indent">
                โดยที่บริษัทเป็นเจ้าของข้อมูลเกี่ยวกับการดำเนินธุรกิจทางการค้า และระบบการบริหารจัดการ
                ทั้งภายในและภายนอกของบริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) ซึ่งต่อไปนี้จะเรียกว่า
                <strong>"ข้อมูลที่เป็นความลับ"</strong> มีความประสงค์ที่จะเปิดเผยข้อมูลดังกล่าวให้แก่พนักงาน
                และพนักงานมีความต้องการที่จะใช้ข้อมูลของบริษัท
                เพื่อการปฏิบัติงานตามตำแหน่งและหน้าที่ในฐานะพนักงานของบริษัท
                ซึ่งบริษัทประสงค์ที่คุ้มครองเรื่องดังกล่าวไว้เป็นข้อมูลที่เป็นความลับ
            </p>

            <p class="indent">
                ด้วยเหตุผลสำคัญในการปฏิบัติงานของพนักงาน ในฐานะพนักงานของบริษัท ย่อมได้รับรู้ข้อมูลที่เป็นความลับ
            </p>

            <p class="indent">
                ในการนี้ เพื่อรักษาความปลอดภัยของข้อมูลและความมั่นใจของ ทั้งสองฝ่ายจึงตกลงทำข้อตกลงนี้ขึ้น
                เพื่อการปฏิบัติตามข้อตกลงต่างๆ ในการไม่เปิดเผยข้อมูล โดยมีเงื่อนไขดังต่อไปนี้
            </p>

            <p><strong>ข้อ 1. ในข้อตกลงนี้ "ข้อมูลที่เป็นความลับ"</strong> หมายความถึง
                ข้อมูลกระบวนบริหารจัดการภายในของบริษัท และข้อมูลเกี่ยวกับธุรกิจ โครงสร้าง ลูกค้า ผู้รับจ้าง
                และกิจการค้าของบริษัท หรือข้อมูลอื่นๆ ที่เกี่ยวข้องกับธุรกิจและกิจการค้าของบริษัท รายงาน ผลการวิเคราะห์
                ความคิดเห็น แบบแปลน เอกสารอื่นใดที่พนักงานได้จัดทำขึ้น หรือได้ทำร่วมกับผู้อื่น
                ไม่ว่าโดยทางตรงหรือโดยทางอ้อม และข้อมูลใดๆสิ่งที่สื่อความหมายให้รู้ข้อความ เรื่องราว ข้อเท็จจริง
                หรือสิ่งใด ไม่ว่าการสื่อความหมายนั้นจะผ่านวิธีการใด ๆ และไม่ว่าจะจัดทำไว้ในรูปใด ๆ
                และให้หมายความรวมถึงสูตร รูปแบบ แนวความคิด งานที่ได้รวบรวมหรือประกอบขึ้น โปรแกรม วิธีการ เทคนิค
                หรือกรรมวิธีประมวลผลด้วยวิธีการทางอิเล็กทรอนิกส์ ด้วย</p>
        </div>

        <div class="footer">
            <div class="footer-left">Kumwell Corporation Public Company Limited</div>
            <div class="footer-right">หน้าที่ 1/4</div>
        </div>
    </div>

    <!-- PAGE 2 -->
    <div class="page">
        <div class="header">
            <div class="logo-text">Kumwell</div>
        </div>
        <div class="content" style="margin-top: 20px;">
            <p class="indent" style="margin-top: 0;">
                รวมทั้งข้อมูลของบุคคลภายในและภายนอกที่บริษัทได้เปิดเผยแก่พนักงาน
                หรือข้อมูลได้รับมาโดยหน้าที่ที่เกี่ยวข้องทั้งทางตรงหรือทางอ้อม
                และบริษัทประสงค์ให้พนักงานเก็บรักษาข้อมูลดังกล่าวไว้เป็นความลับ และความลับทางการค้าของบริษัท
                โดยข้อมูลดังกล่าวจะเกี่ยวข้องกับข้อมูลคู่ค้า ข้อมูลการเงินและบัญชี หรือแผนทางการตลาด หรือแผนธุรกิจต่างๆ
                ซึ่งรวมถึงแต่ไม่จำกัดเฉพาะกระบวนการ ขั้นตอนวิธี โปรแกรมคอมพิวเตอร์ แบบ ต้นแบบ ภาพวาด สูตร เทคนิค
                การพัฒนาผลิตภัณฑ์ ข้อมูลการทดลอง และข้อมูลอื่นใดที่เกี่ยวข้องกับข้อมูลที่เป็นความลับดังกล่าว ถือว่า
                มีความสำคัญยิ่ง และเป็นความลับที่มิอาจเปิดเผยต่อสาธารณะได้ และห้ามเปิดเผยแก่บุคคลภายนอก หรือองค์กรที่ 3
                โดยเด็ดขาด พนักงานทราบดีว่า ข้อมูลที่เป็นความลับเป็นกรรมสิทธิ์ของบริษัทโดยแท้
            </p>

            <p><strong>ข้อ 2. พนักงานตกลงรักษาไว้ซึ่งข้อมูลที่เป็นความลับ</strong> ทั้งในรูปแบบข่าวสาร และข้อมูลใด ๆ
                ของบริษัท ตลอดถึงข้อมูลที่เกี่ยวกับเทคนิค เทคโนโลยี ข้อมูลลูกค้า ข้อมูลผู้รับจ้าง
                ข้อมูลทางการเงินหรือเรื่องอื่นใดที่พนักงานได้รับรู้รับทราบ
                เนื่องจากการปฏิบัติงานให้แก่บริษัทตลอดระยะเวลาที่ข้อมูลยังเป็นของบริษัท
                และไม่เปิดเผยข้อมูลที่เป็นความลับใด ๆ
                แก่บุคคลที่สามโดยไม่ได้รับความยินยอมเป็นลายลักษณ์อักษรล่วงหน้าจากบริษัท
                โดยพนักงานจะไม่นำเอาข้อมูลที่เป็นความลับไปใช้ ในประการที่อาจทำให้บริษัทเกิดความเสียหาย หรือเสียประโยชน์
            </p>

            <p><strong>ข้อ 3. พนักงานตกลงจะดำเนินการตามขั้นตอนที่จำเป็นอย่างสุดความสามารถ</strong>
                เพื่อหลีกเลี่ยงมิให้ข้อมูลที่เป็นความลับถูกเปิดเผย และใช้ความระมัดระวังอย่างยิ่ง
                เพื่อป้องกันบุคคลที่เกี่ยวข้องเข้าถึงข้อมูลอันเป็นความลับนั้น</p>

            <p><strong>ข้อ 4. ข้อจำกัดในการใช้ข้อมูล</strong>
                ข้อมูลที่บริษัทหรือในนามของบริษัทที่เปิดเผยแก่พนักงานให้ใช้เพื่อการปฏิบัติงานตามตำแหน่งและหน้าที่ในฐานะพนักงานของบริษัทเท่านั้น
                โดยห้ามมิให้ใช้เพื่อดังต่อไปนี้</p>
            <div class="list-item">4.1 ห้ามใช้เพื่อหาประโยชน์ส่วนตัว</div>
            <div class="list-item">4.2 ห้ามใช้เพื่อหาประโยชน์ในการร่วมมือกับบุคคลหรือองค์กรอื่นใด</div>
            <div class="list-item">4.3 ห้ามใช้เพื่อวัตถุประสงค์ในเชิงพาณิชย์</div>
            <div class="list-item">4.4 ห้ามใช้ หรือพยายามที่จะใช้ข้อมูลหรือสิ่งที่ได้มาจากข้อมูลเพื่อการอื่นใด
                โดยไม่ได้รับอนุญาตจากผู้ให้ข้อมูล</div>
            <div class="list-item">4.5 ห้ามอ้างถึงหรือรวมเข้าไปเป็นส่วนหนึ่งของการประดิษฐ์ใด ๆ
                นอกจากจะได้รับอนุญาตจากผู้ให้ข้อมูล หรือขอรับความคุ้มครองทรัพย์สินทางปัญญาใด ๆ
                ในนามของผู้รับข้อมูลหรือผู้อื่นผู้ใดยกเว้นผู้ให้ข้อมูล</div>

            <p><strong>ข้อ 5. พนักงานจะไม่กระทำ หรืองดเว้นกระทำ หรือยอมให้ผู้อื่น</strong> เพื่อการคัดลอก ดัดแปลง ทำซ้ำ
                แก้ไข เพิ่มเติม อัดหรือทำสำเนา ถ่ายภาพ หรือกระทำการอื่นใดกับเอกสารดังกล่าวของบริษัทโดยเด็ดขาด
                เว้นแต่จะได้รับอนุญาตเป็นลายลักษณ์อักษรจากทางบริษัทแล้วเท่านั้น</p>
        </div>
        <div class="footer">
            <div class="footer-left">Kumwell Corporation Public Company Limited</div>
            <div class="footer-right">หน้าที่ 2/4</div>
        </div>
    </div>

    <!-- PAGE 3 -->
    <div class="page">
        <div class="header">
            <div class="logo-text">Kumwell</div>
        </div>
        <div class="content" style="margin-top: 20px;">
            <p style="margin-top: 0;"><strong>ข้อ 6. ภายใต้การปฏิบัติงาน</strong> บริษัทมีสิทธิทำการตรวจสอบ เรียกดู
                ข้อมูลที่เป็นความลับตามที่ข้อ 2. ที่พนักงานเก็บรักษาไว้
                และมีสิทธิเรียกร้องต่อพนักงานให้ดำเนินการแก้ไขหรือตามมาตรการที่กำหนด
                หากตรวจพบข้อมูลที่เป็นความลับที่ไม่ถูกต้อง
                นอกจากนี้บริษัทยังมีสิทธิเรียกร้องต่อพนักงานให้แก้ไขปรับปรุงนโยบายและมาตรการปฏิบัติงานของพนักงาน
                ในส่วนที่เกี่ยวข้องกับการเก็บรักษาข้อมูลของบริษัท</p>

            <p><strong>ข้อ 7. เมื่อได้รับการร้องจากบริษัท หรือหน่วยงานตัวแทนของบริษัท การนั้น</strong>
                พนักงานมีหน้าที่ต้องส่งมอบทรัพย์สินและเอกสารทั้งหมด ซึ่งเป็นข้อมูลที่เป็นความลับ ที่เป็นของบริษัท
                และข้อมูลที่บริษัทเจ้าของกรรมสิทธิ์ ที่อยู่ในการครอบครองของพนักงานให้แก่บริษัท โดยพลันทันที</p>

            <p><strong>ข้อ 8. ข้อตกลงรักษาความลับนี้มีผลบังคับตั้งแต่วันที่คู่สัญญาทั้งสองฝ่ายลงนาม</strong>
                โดยให้ข้อตกลงฉบับนี้มีผลตั้งแต่เริ่มเข้าทำงานในบริษัท
                และพนักงานจะเปิดเผยข้อมูลที่เป็นความลับได้ต่อเมื่อได้รับความยินยอมเป็นหนังสือจากฝ่ายผู้ให้ข้อมูลดังกล่าวก่อน
                หรือจนกว่าข้อมูลที่เป็นความลับนั้นกลายเป็นข้อมูลที่ไม่ใช่ความลับโดยชอบด้วยกฎหมาย</p>

            <p><strong>ข้อ 9. การชดใช้ค่าเสียหาย</strong> หากพนักงานปฏิบัติ หรือละเว้นการปฏิบัติหน้าที่
                หรือปฏิบัติผิดฝ่าฝืนข้อตกลงรักษาข้อมูลความลับตามข้อตกลงนี้ข้อหนึ่งข้อใด หรือกระทำการใดๆ
                เป็นเหตุให้เกิดความเสียหายแก่บริษัท
                พนักงานตกลงยินยอมชดใช้ค่าเสียหายแก่บริษัททั้งหมดภายในกำหนดเวลาที่บริษัทเรียกร้อง
                และ/หรือบุคคลที่ได้รับความเสียหายสำหรับความเสียหายเช่นว่านั้น
                และบริษัทมีสิทธิลงโทษทางวินัยแก่พนักงานตามระเบียบข้อบังคับของบริษัท</p>

            <p><strong>ข้อ 10. ในกรณีที่ข้อตกลงนี้ข้อใดข้อหนึ่งหรือหลายข้อแห่งข้อตกลงนี้ตกเป็นอันไม่สมบูรณ์</strong>
                หรือตกเป็นโมฆะด้วยเหตุใด ๆ ก็ตาม
                ความไม่สมบูรณ์หรือความเป็นโมฆะของข้อตกลงเช่นว่านี้จะไม่กระทบกระเทือนถึงความสมบูรณ์ของข้อตกลงนี้ในส่วนอื่น
                ๆ</p>
        </div>
        <div class="footer">
            <div class="footer-left">Kumwell Corporation Public Company Limited</div>
            <div class="footer-right">หน้าที่ 3/4</div>
        </div>
    </div>

    <!-- PAGE 4 -->
    <div class="page">
        <div class="header">
            <div class="logo-text">Kumwell</div>
        </div>
        <div class="content" style="margin-top: 20px;">
            <p class="indent" style="margin-top: 0;">
                ข้อตกลงนี้ มีข้อ 1. ถึงข้อ 10. จำนวน 4 หน้า และถูกจัดทำขึ้น 1 ฉบับ คู่สัญญาได้อ่าน
                และเข้าใจข้อความในสัญญาโดยละเอียดแล้ว จึงได้ลงลายมือชื่อพร้อมทั้งประทับตรา (ถ้ามี) ไว้เป็นสำคัญ
                และต่างยึดถือไว้ฝ่ายละหนึ่งฉบับ
            </p>
        </div>

        <table class="signature-table">
            <tr>
                <td class="signature-cell">
                    <div class="sig-area">
                        @if($existing->company_signature && str_starts_with($existing->company_signature, 'data:image'))
                            <img src="{{ $existing->company_signature }}" class="sig-image">
                        @elseif($existing->is_auto_sign && $manager && $manager->signature_url)
                            <img src="{{ $manager->signature_url }}" class="sig-image">
                        @endif
                    </div>
                    <div class="sig-label">
                        <div class="sig-label-wrapper">
                            ลงชื่อ............................................................บริษัท
                            @if($existing->company_agreed_at && !str_starts_with($existing->company_signature ?? '', 'data:image') && !$existing->is_auto_sign)
                                <div class="sig-typed-name">{{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="sig-name">(คุณ{{ $manager->fullname ?? 'เกรียงศักดิ์ อำนวยโชค' }})</div>
                    <div class="sig-name">{{ $manager->department_name ?? 'ผู้จัดการแผนกเทคโนโลยีสารสนเทศและการสื่อสาร' }}</div>
                    <div class="sig-name"><strong>บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)</strong></div>
                </td>
                <td class="signature-cell">
                    <div class="sig-area">
                        @if($existing->employee_signature && str_starts_with($existing->employee_signature, 'data:image'))
                            <img src="{{ $existing->employee_signature }}" class="sig-image">
                        @elseif($existing->user && $existing->user->signature_url && (!$existing->employee_signature || str_starts_with($existing->employee_signature, 'data:image')))
                            <img src="{{ $existing->user->signature_url }}" class="sig-image">
                        @endif
                    </div>
                    <div class="sig-label">
                        <div class="sig-label-wrapper">
                            ลงชื่อ............................................................พนักงาน
                            @if($existing->employee_signature && !str_starts_with($existing->employee_signature, 'data:image'))
                                <div class="sig-typed-name">{{ $existing->employee_signature }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="sig-name">({{ $existing->prefix }}{{ $existing->full_name }})</div>
                </td>
            </tr>
            <tr style="height: 30px;"></tr>
            <tr>
                <td class="signature-cell">
                    <div class="sig-area">
                        @if($existing->witness1_signature && str_starts_with($existing->witness1_signature, 'data:image'))
                            <img src="{{ $existing->witness1_signature }}" class="sig-image">
                        @elseif($existing->witness1 && $existing->witness1->signature_url && (!$existing->witness1_signature || str_starts_with($existing->witness1_signature, 'data:image')))
                            <img src="{{ $existing->witness1->signature_url }}" class="sig-image">
                        @endif
                    </div>
                    <div class="sig-label">
                        <div class="sig-label-wrapper">
                            ลงชื่อ............................................................พยาน
                            @if($existing->witness1_agreed_at && !str_starts_with($existing->witness1_signature ?? '', 'data:image'))
                                <div class="sig-typed-name">{{ $existing->witness1_signature ?? $existing->witness1_name }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="sig-name">({{ $existing->witness1_name }})</div>
                </td>
                <td class="signature-cell">
                    <div class="sig-area">
                        @if($existing->witness2_signature && str_starts_with($existing->witness2_signature, 'data:image'))
                            <img src="{{ $existing->witness2_signature }}" class="sig-image">
                        @elseif($existing->witness2 && $existing->witness2->signature_url && (!$existing->witness2_signature || str_starts_with($existing->witness2_signature, 'data:image')))
                            <img src="{{ $existing->witness2->signature_url }}" class="sig-image">
                        @endif
                    </div>
                    <div class="sig-label">
                        <div class="sig-label-wrapper">
                            ลงชื่อ............................................................พยาน
                            @if($existing->witness2_agreed_at && !str_starts_with($existing->witness2_signature ?? '', 'data:image'))
                                <div class="sig-typed-name">{{ $existing->witness2_signature ?? $existing->witness2_name }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="sig-name">
                        @if($existing->witness2_user_id)
                            ({{ $existing->witness2_name }})
                        @else
                            <span style="color: #999;">(............................................................)</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div class="footer-left">Kumwell Corporation Public Company Limited</div>
            <div class="footer-right">หน้าที่ 4/4</div>
        </div>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);

            window.onafterprint = function () {
                window.close();
            };
        }
    </script>
</body>

</html>