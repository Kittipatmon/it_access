<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>IT Access Request Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm;
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: local('TH Sarabun New'), local('THSarabunNew'), url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: local('TH Sarabun New Bold'), local('THSarabunNew-Bold'), url("{{ asset('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 18px;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .logo {
            float: right;
            margin-bottom: 10px;
        }

        .logo img {
            height: 45px;
        }

        .text-center {
            text-align: center;
            clear: both;
        }

        .font-bold {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            font-size: 20px;
            margin-top: 15px;
            margin-bottom: 10px;
            clear: both;
        }

        .page {
            position: relative;
            min-height: 265mm;
            box-sizing: border-box;
        }

        .doc-no {
            position: absolute;
            bottom: -20px;
            right: 0;
            font-size: 16px;
        }

        .form-row {
            margin-bottom: 25px;
        }

        .flex-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 25px;
            width: 100%;
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
            bottom: 5px;
            border-bottom: 1px dotted #000;
        }

        .solid-line {
            display: inline-block;
            text-align: center;
            position: relative;
        }

        .solid-line::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 5px;
            border-bottom: 1px solid #000;
        }

        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            text-align: center;
            line-height: 14px;
            font-size: 14px;
            vertical-align: text-bottom;
            margin-right: 3px;
            background: #fff;
        }

        .box-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .box-cell {
            width: 48%;
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }

        .box-gap {
            width: 4%;
        }

        .status-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .status-cell {
            width: 48%;
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
        }

        .status-gap {
            width: 4%;
        }

        .page-break {
            page-break-before: always;
        }

        ol,
        ul {
            margin-left: 15px;
            padding-left: 25px;
        }

        li {
            margin-bottom: 10px;
            line-height: 1.4;
            text-align: left;
        }
    </style>
</head>

<body onload="window.print()">
    @php
        $sysAccess = $request->system_access ?? [];
        $progAccess = $request->program_access ?? [];
        $itSys = $request->it_system_config ?? [];
        $itProg = $request->it_program_config ?? [];

        function isChecked($arr, $key)
        {
            return isset($arr[$key]) && $arr[$key] ? '✔' : '&nbsp;&nbsp;';
        }
    @endphp
    <!-- ==================== PAGE 1 ==================== -->
    <div class="page">

        <div
            style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px; margin-top: 10px;">
            <div class="font-bold" style="font-size: 16px;">
                Req.No <span class="dot-line" style="width: 200px; text-align: left;">{{ $request->request_no }}</span>
            </div>
            <div>
                <span
                    style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #d31221; letter-spacing: -1px; margin-right: 15px;">Kumwell</span>
            </div>
        </div>

        <div class="text-center font-bold" style="font-size: 24px; margin: 45px 0 20px 0;">
            แบบฟอร์มการร้องขอสิทธิใช้งานเทคโนโลยีสารสนเทศ
        </div>

        <div class="section-title">ส่วนที่ 1 ผู้ร้องขอ</div>

        <div class="form-row" style="margin-bottom: 25px;">
            <span class="checkbox">{{ $request->request_type == 'new_employee' ? '✔' : '' }}</span>
            พนักงานใหม่ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checkbox">{{ $request->request_type == 'resign' ? '✔' : '' }}</span> ลาออก
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checkbox">{{ $request->request_type == 'position_change' ? '✔' : '' }}</span> ปรับตำแหน่ง
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checkbox">{{ $request->request_type == 'transfer' ? '✔' : '' }}</span> โอนย้าย
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checkbox">{{ $request->request_type == 'add_remove_access' ? '✔' : '' }}</span>
            เพิ่มสิทธิ์/ลบสิทธิ์
        </div>

        <div class="flex-row">
            <span>ชื่อ :&nbsp;</span><span class="dot-line" style="flex: 2;">{{ $request->firstname }}</span>
            <span>&nbsp;&nbsp;นามสกุล&nbsp;</span><span class="dot-line"
                style="flex: 2;">{{ $request->lastname }}</span>
            <span>&nbsp;&nbsp;ชื่อเล่น :&nbsp;</span><span class="dot-line"
                style="flex: 1;">{{ $request->nickname_th }}</span>
        </div>
        <div class="flex-row">
            <span>Name :&nbsp;</span><span class="dot-line" style="flex: 2;">{{ $request->firstname_en }}</span>
            <span>&nbsp;&nbsp;Last Names:&nbsp;</span><span class="dot-line"
                style="flex: 2;">{{ $request->lastname_en }}</span>
            <span>&nbsp;&nbsp;Nick Name:&nbsp;</span><span class="dot-line"
                style="flex: 1;">{{ $request->nickname_en }}</span>
        </div>
        <div class="flex-row">
            <span>โทรศัพท์&nbsp;</span><span class="dot-line" style="flex: 1;">{{ $request->phone }}</span>
            <span>&nbsp;&nbsp;ภายใน&nbsp;</span><span class="dot-line" style="flex: 1;">{{ $request->phone_ext }}</span>
        </div>
        <div class="flex-row">
            <span>ระดับ : &nbsp;&nbsp;</span>
            <span class="checkbox">{{ $request->position_level == 'executive' ? '✔' : '' }}</span><span> ผู้บริหาร
                &nbsp;&nbsp;&nbsp;</span>
            <span class="checkbox">{{ $request->position_level == 'department_head' ? '✔' : '' }}</span><span>
                หัวหน้าแผนก &nbsp;&nbsp;&nbsp;</span>
            <span class="checkbox">{{ $request->position_level == 'employee' ? '✔' : '' }}</span><span> พนักงานทั่วไป
                &nbsp;&nbsp;&nbsp;</span>
            <span class="checkbox">{{ $request->position_level == 'other' ? '✔' : '' }}</span><span> อื่น&nbsp;</span>
            <span class="dot-line"
                style="flex: 1;">{{ $request->position_level == 'other' ? $request->position_level_other : '' }}</span>
        </div>
        <div class="flex-row">
            <span>ตำแหน่ง :&nbsp;</span><span class="dot-line" style="flex: 1;">{{ $request->position_name }}</span>
            <span>&nbsp;&nbsp;แผนก :&nbsp;</span><span class="dot-line"
                style="flex: 1;">{{ $request->department_name }}</span>
        </div>
        <div class="flex-row" style="margin-bottom: 35px;">
            <span>ฝ่าย :&nbsp;</span><span class="dot-line" style="flex: 1;">{{ $request->division_name }}</span>
        </div>

        <hr style="border-top: 1px solid #ccc; margin-bottom: 20px;">

        <div class="section-title">ส่วนที่ 2 การเข้าถึง</div>

        <table class="box-table" style="height: 250px;">
            <tr>
                <td class="box-cell">
                    <div class="font-bold" style="margin-bottom: 25px;">การเข้าถึงระบบ</div>

                    <div style="margin-bottom: 30px;">[ {!! isChecked($sysAccess, 'login_computer') !!} ]
                        Username&amp;Passsword Login Computer</div>
                    <div style="margin-bottom: 30px;">[ {!! isChecked($sysAccess, 'email_address') !!} ] Email Address
                    </div>

                    <div style="margin-bottom: 30px;">
                        File Server Access<br><br>
                        &nbsp;&nbsp;&nbsp;[
                        {!! isChecked($sysAccess, 'file_server_access_sub') == '✔' && in_array('super_user', $sysAccess['file_server_access_sub'] ?? []) ? '✔' : '&nbsp;&nbsp;' !!}
                        ] Super User &nbsp;&nbsp;
                        [
                        {!! isChecked($sysAccess, 'file_server_access_sub') == '✔' && in_array('admin', $sysAccess['file_server_access_sub'] ?? []) ? '✔' : '&nbsp;&nbsp;' !!}
                        ] Admin &nbsp;
                        [
                        {!! isChecked($sysAccess, 'file_server_access_sub') == '✔' && in_array('user', $sysAccess['file_server_access_sub'] ?? []) ? '✔' : '&nbsp;&nbsp;' !!}
                        ] User
                    </div>

                    <div>[ {!! isChecked($sysAccess, 'other_check') !!} ] Other &nbsp; <span class="dot-line"
                            style="width: 140px;">{{ $sysAccess['other_text'] ?? '' }}</span></div>
                </td>
                <td class="box-gap"></td>
                <td class="box-cell">
                    <div class="font-bold" style="margin-bottom: 25px;">การเข้าถึงโปรแกรม</div>

                    <div style="margin-bottom: 30px;">[ {!! isChecked($progAccess, 'sap_b1') !!} ] SAP B1 Username
                        &amp;Password</div>
                    <div style="margin-bottom: 30px;">
                        [ {!! isChecked($progAccess, 'sap_b1') !!} ] SAP B1: level &nbsp;&nbsp;&nbsp;
                        <span
                            class="checkbox">{!! isChecked($progAccess, 'sap_b1_sub') == '✔' && in_array('pro', $progAccess['sap_b1_sub'] ?? []) ? '✔' : '' !!}</span>
                        Pro &nbsp;&nbsp;
                        <span
                            class="checkbox">{!! isChecked($progAccess, 'sap_b1_sub') == '✔' && in_array('crm', $progAccess['sap_b1_sub'] ?? []) ? '✔' : '' !!}</span>
                        CRM
                    </div>
                    <div style="margin-bottom: 30px;">
                        &nbsp;&nbsp;&nbsp;<span
                            class="checkbox">{!! isChecked($progAccess, 'sap_b1_sub') == '✔' && in_array('logistics', $progAccess['sap_b1_sub'] ?? []) ? '✔' : '' !!}</span>
                        Logistics &nbsp;&nbsp;
                        <span
                            class="checkbox">{!! isChecked($progAccess, 'sap_b1_sub') == '✔' && in_array('financials', $progAccess['sap_b1_sub'] ?? []) ? '✔' : '' !!}</span>
                        Financials
                    </div>
                    <div style="margin-bottom: 30px;">[ {!! isChecked($progAccess, 'rapid_payroll') !!} ] Rapid payroll
                    </div>
                    <div>[ {!! isChecked($progAccess, 'other_check') !!} ] Other <span class="dot-line"
                            style="width: 140px;">{{ $progAccess['other_text'] ?? '' }}</span></div>
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px;">
            @php
                $requesterStep = $request; // just use created_at
                $reviewerStep = $request->steps->where('step_order', 1)->first();
                $approverStep = $request->steps->where('step_order', 2)->first();
            @endphp
            <div class="flex-row" style="padding-left: 20px; position: relative;">
                <span>ผู้ร้องขอ&nbsp;</span>
                @if($request->signature_path)
                    <img src="{{ asset('storage/' . $request->signature_path) }}"
                        style="height: 30px; position: absolute; left: 80px; top: -10px;">
                @endif
                <span class="dot-line" style="flex: 2;">
                    @if(!$request->signature_path) {{ $request->firstname }} {{ $request->lastname }} @endif
                </span>
                <span>&nbsp;&nbsp;วันที่&nbsp;</span><span class="dot-line"
                    style="flex: 1;">{{ $request->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="flex-row" style="padding-left: 20px; position: relative;">
                <span>ผู้ตรวจสอบ&nbsp;</span>
                @if($reviewerStep && $reviewerStep->status == 'approved' && $reviewerStep->approver && $reviewerStep->approver->signature)
                    <img src="{{ asset('storage/signatures/' . $reviewerStep->approver->signature) }}"
                        style="height: 30px; position: absolute; left: 90px; top: -10px;">
                @endif
                <span class="dot-line" style="flex: 2;"></span>
                <span>&nbsp;&nbsp;วันที่&nbsp;</span><span class="dot-line"
                    style="flex: 1;">{{ $reviewerStep && $reviewerStep->status == 'approved' ? $reviewerStep->approved_at->format('d/m/Y') : '......../......../........' }}</span>
            </div>
            <div class="flex-row" style="padding-left: 20px; position: relative;">
                <span>ผู้อนุมัติ&nbsp;</span>
                @if($approverStep && $approverStep->status == 'approved' && $approverStep->approver && $approverStep->approver->signature)
                    <img src="{{ asset('storage/signatures/' . $approverStep->approver->signature) }}"
                        style="height: 30px; position: absolute; left: 80px; top: -10px;">
                @endif
                <span class="dot-line" style="flex: 2;"></span>
                <span>&nbsp;&nbsp;วันที่&nbsp;</span><span class="dot-line"
                    style="flex: 1;">{{ $approverStep && $approverStep->status == 'approved' ? $approverStep->approved_at->format('d/m/Y') : '......../......../........' }}</span>
            </div>
        </div>

        <div class="doc-no">
            QF-IT-08: Rev: 02: 06-07-20
        </div>
    </div>

    <!-- ==================== PAGE 2 ==================== -->
    <div class="page-break"></div>

    <div class="page">
        <div style="text-align: right; margin-bottom: 5px;">
            <span
                style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #d31221; letter-spacing: -1px; margin-right: 15px;">Kumwell</span>
        </div>

        <div class="section-title" style="margin-top: 20px;">ส่วนที่ 3 สำหรับเจ้าหน้าที่</div>
        <div class="form-row" style="text-indent: 40px; margin-bottom: 10px;">
            เจ้าหน้าที่ได้ดำเนินการ สร้าง/เพิ่ม/แก้ไข ตามข้อมูลข้างต้นเป็นที่เรียบร้อยแล้ว โดยมีรายละเอียดดังนี้
        </div>

        <table class="box-table">
            <tr>
                <td class="box-cell" style="padding: 10px 15px;">
                    <div class="font-bold" style="margin-bottom: 15px;">การเข้าถึงระบบ</div>

                    @php 
                        $itSys = $request->it_system_config ?? []; 
                        $displayedKeys = [];
                    @endphp

                    @foreach($itSys as $configKey => $configVal)
                        @if(Str::endsWith($configKey, '_check'))
                            @php 
                                $itemKey = str_replace('_check', '', $configKey);
                                $opt = $accessOptions->where('key', $itemKey)->first();
                                $displayedKeys[] = $itemKey;
                            @endphp
                            <div style="margin-bottom: 15px;">
                                [ ✔ ] {{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $itemKey)) }}
                            </div>
                            <div style="margin-bottom: 15px; padding-left: 20px;">
                                @if($opt && $opt->custom_fields)
                                    @foreach($opt->custom_fields as $field)
                                        @php $val = $itSys[$itemKey . '_' . Str::snake($field)] ?? ''; @endphp
                                        <div style="margin-bottom: 8px;">
                                            {{ $field }}: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $val }}</span>
                                        </div>
                                    @endforeach
                                @elseif(in_array($itemKey, ['login_computer', 'email']))
                                    <div style="margin-bottom: 8px;">User Name: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $itSys[$itemKey.'_user'] ?? '' }}</span></div>
                                    <div style="margin-bottom: 8px;">Password: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $itSys[$itemKey.'_pass'] ?? '' }}</span></div>
                                @elseif($itemKey === 'file_server')
                                    <span class="solid-line" style="width: 250px;">อนุญาตแล้ว</span>
                                @else
                                    <span class="solid-line" style="width: 250px;">{{ is_string($configVal) ? $configVal : '' }}</span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </td>
                <td class="box-gap"></td>
                <td class="box-cell" style="padding: 10px 15px;">
                    <div class="font-bold" style="margin-bottom: 15px;">การเข้าถึงโปรแกรม</div>

                    @php $itProg = $request->it_program_config ?? []; @endphp

                    @foreach($itProg as $configKey => $configVal)
                        @if(Str::endsWith($configKey, '_check'))
                            @php 
                                $itemKey = str_replace('_check', '', $configKey);
                                $opt = $accessOptions->where('key', $itemKey)->first();
                            @endphp
                            <div style="margin-bottom: 15px;">
                                [ ✔ ] {{ $opt ? $opt->name : ucwords(str_replace('_', ' ', $itemKey)) }}
                            </div>
                            <div style="margin-bottom: 15px; padding-left: 20px;">
                                @if($opt && $opt->custom_fields)
                                    @foreach($opt->custom_fields as $field)
                                        @php $val = $itProg[$itemKey . '_' . Str::snake($field)] ?? ''; @endphp
                                        <div style="margin-bottom: 8px;">
                                            {{ $field }}: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $val }}</span>
                                        </div>
                                    @endforeach
                                @elseif(in_array($itemKey, ['sap_b1', 'rapid_payroll']))
                                    <div style="margin-bottom: 8px;">User Name: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $itProg[$itemKey.'_user'] ?? '' }}</span></div>
                                    @if($itemKey === 'sap_b1' && isset($itProg['sap_b1_level']))
                                        <div style="margin-bottom: 8px;">Level: <span class="solid-line" style="width: 180px; display: inline-block;">{{ $itProg['sap_b1_level'] }}</span></div>
                                    @endif
                                @else
                                    <span class="solid-line" style="width: 250px;">{{ is_string($configVal) ? $configVal : '' }}</span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                    @endforeach
                </td>
            </tr>
        </table>

        <table class="status-table">
            <tr>
                <td class="status-cell text-center" style="padding: 15px;">
                    <div class="font-bold" style="margin-bottom: 10px;">สถานะ : [
                        {!! ($itSys['status'] ?? '') == 'completed' ? '✔' : '&nbsp;&nbsp;' !!} ] Complete
                        &nbsp;&nbsp;&nbsp;
                        [ {!! ($itSys['status'] ?? '') != 'completed' ? '✔' : '&nbsp;&nbsp;' !!} ] Pending
                    </div>
                    <div style="margin-bottom: 10px;">ดำเนินการโดย : <span class="dot-line"
                            style="width: 180px;">{{ $request->itStaff->fullname ?? '' }}</span></div>
                    <div>วันที่<span class="dot-line"
                            style="width: 150px;">{{ $request->it_configured_at ? $request->it_configured_at->format('d/m/Y') : '......../......../........' }}</span>
                    </div>
                </td>
                <td class="status-gap"></td>
                <td class="status-cell text-center" style="padding: 15px;">
                    <div class="font-bold" style="margin-bottom: 10px;">สถานะ : [
                        {!! ($itProg['status'] ?? '') == 'completed' ? '✔' : '&nbsp;&nbsp;' !!} ] Complete
                        &nbsp;&nbsp;&nbsp; [ {!! ($itProg['status'] ?? '') != 'completed' ? '✔' : '&nbsp;&nbsp;' !!} ]
                        Pending
                    </div>
                    <div style="margin-bottom: 10px;">ดำเนินการโดย : <span class="dot-line"
                            style="width: 180px;">{{ $request->itStaff->fullname ?? '' }}</span></div>
                    <div>วันที่<span class="dot-line"
                            style="width: 150px;">{{ $request->it_configured_at ? $request->it_configured_at->format('d/m/Y') : '......../......../........' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <hr style="border-top: 1px solid #ccc; margin-top: 20px; margin-bottom: 15px;">

        <div class="section-title">ส่วนที่ 4 สำหรับผู้ใช้งาน</div>
        <div class="form-row" style="text-indent: 40px; line-height: 1.8; margin-bottom: 20px;">
            ข้าพเจ้าได้รับข้อมูลผู้ใช้งานและรหัสผ่านเป็นที่เรียบร้อยแล้ว
            และได้ทำการเปลี่ยนแปลงแก้ไขรหัสผ่านในครั้งแรกที่เข้าใช้งานและจะเก็บข้อมูลดังกล่าวเป็นความลับ
        </div>

        <div class="flex-row" style="padding-left: 20px; position: relative;">
            <span>ผู้ใช้งาน&nbsp;</span>
            @if($request->user_acknowledged_at && $request->user && $request->user->signature)
                <img src="{{ asset('storage/signatures/' . $request->user->signature) }}"
                    style="height: 30px; position: absolute; left: 80px; top: -10px;">
            @endif
            <span class="dot-line" style="flex: 2;"></span>
            <span>&nbsp;&nbsp;วันที่&nbsp;</span><span class="dot-line"
                style="flex: 1;">{{ $request->user_acknowledged_at ? $request->user_acknowledged_at->format('d/m/Y') : '......../......../........' }}</span>
        </div>

        <div class="doc-no">
            QF-IT-08: Rev: 02: 06-07-20
        </div>
    </div>

    <!-- ==================== PAGE 3 ==================== -->
    <div class="page-break"></div>

    <div class="page">
        <div style="text-align: right; margin-bottom: 5px;">
            <span
                style="font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; color: #d31221; letter-spacing: -1px; margin-right: 15px;">Kumwell</span>
        </div>

        <div class="text-center font-bold" style="font-size: 22px; margin-top: 40px; margin-bottom: 15px;">
            ประกาศ
        </div>
        <div class="text-center font-bold" style="font-size: 22px; margin-bottom: 30px;">
            ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์
        </div>

        <div style="text-indent: 40px; margin-bottom: 25px; line-height: 2; text-align: justify;">
            บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)
            ได้จัดให้มีระบบเครือข่ายและคอมพิวเตอร์เพื่อสนับสนุนการดำเนินงานของบริษัทฯ
            ให้เกิดประสิทธิภาพ สามารถตอบสนองเป้าหมายทางธุรกิจในระยะสั้นและระยะยาว ดังนั้น บริษัทฯ
            จึงถือว่าระบบเครือข่ายและ
            คอมพิวเตอร์เป็นสินทรัพย์ที่สำคัญของบริษัทฯ เพื่อให้การใช้งานเป็นไปอย่างเรียบร้อยและเกิดประโยชน์สูงสุด
            บริษัทฯ
            จึงได้ประกาศ
            ระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ โดยมีสาระสำคัญดังนี้
        </div>

        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">1.</td>
                <td style="line-height: 2; text-align: justify;">
                    บริษัทฯ มอบหมายแผนกเทคโนโลยีสารสนเทศ ดูแลบำรุงรักษา
                    พัฒนาและปรับปรุงระบบเครือข่ายและคอมพิวเตอร์ให้สามารถ
                    ใช้งานได้ดีอยู่เสมอ พร้อมทั้งเปิดบัญชีผู้ใช้งาน (User Account) และรหัสผ่าน (Password)
                    ให้กับผู้ใช้งานเฉพาะบุคคล โดย
                    ผู้ใช้งานจะต้องปฏิบัติ ดังนี้
                    <table style="width: 100%; border: none; margin-top: 10px;">
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">a.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                ผู้ใช้งาน (User Account) และรหัสผ่าน (Password) ถือเป็นความลับเฉพาะบุคคล
                                ต้องเก็บและรักษาเป็นความลับ ไม่ให้ผู้อื่น
                                สามารถนำไปใช้งานได้อย่างเด็ดขาด
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">b.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                ผู้ใช้งานจะต้องเก็บรักษารหัสผ่าน (Password) ไว้เป็นความลับ
                                โดยไม่กระทำการใดที่แสดงถึงการเปิดเผยรหัสผ่าน
                                (Password) ให้ผู้อื่นทราบ และไม่ใช้โปรแกรมคอมพิวเตอร์ช่วยจำรหัสผ่านอัตโนมัติ (Save
                                Password)
                                สำหรับ
                                เครื่องคอมพิวเตอร์ที่ผู้ใช้งานใช้ปฏิบัติงาน
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">c.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                ผู้ใช้งานต้องไม่ติดตั้ง Software หรือนำอุปกรณ์ Hardware
                                ส่วนบุคคลที่ไม่เกี่ยวข้องกับงานและนอกเหนือจากที่บริษัทฯ
                                กำหนด หากมีความจำเป็นต้องแจ้งผู้บังคับบัญชา และแผนกเทคโนโลยีสารสนเทศ
                                เพื่อขออนุมัติตามลำดับ
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">d.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                ผู้ใช้งานต้องไม่ คัดลอก เปิดเผย หรือถ่ายโอนข้อมูลทั้งในรูปแบบการ Upload , Download
                                หรือวิธีการใดที่ก่อให้เกิดความ
                                เสียหายแก่บริษัทฯ โดยเด็ดขาด ดังเช่น ข้อมูล
                                สื่อสิ่งพิมพ์อิเล็กทรอนิกส์ที่เป็นการละเมิดทรัพย์สินทางปัญญาของผู้เป็น
                                เจ้าของ , ข้อมูลที่เป็นความลับอันประกอบด้วยข้อมูลบริษัท พนักงาน หรือบุคคลภายนอก เป็นต้น
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td
                    style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top; padding-top: 15px;">
                    2.
                </td>
                <td style="line-height: 2; text-align: justify; padding-top: 15px;">
                    เมื่อผู้ใช้งานว่างเว้นจากการใช้งานเครื่องคอมพิวเตอร์ส่วนบุคคลเกิน 5 นาที ให้ผู้ใช้งานทำการพักหน้าจอ
                    (Lock Screen) หรือทำ
                    การปิดเครื่อง (Shut Down) เมื่อการปฏิบัติงานประจำวันเสร็จสิ้น โดย
                    <table style="width: 100%; border: none; margin-top: 10px;">
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">a.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                กรณีที่ผู้ใช้งานยินยอมให้ผู้อื่นใช้งานเครื่องคอมพิวเตอร์ส่วนบุคคลผู้ใช้งานจะต้องทำการออกจากระบบ
                                (Logout) ทันที
                                เพื่อให้ผู้อื่นทำการลงชื่อเข้าใช้งาน (Login) ผ่านบัญชีผู้ใช้งาน (User Account)
                                และรหัสผ่าน
                                (Password) ของตนเองเท่านั้น
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top;">b.
                            </td>
                            <td style="line-height: 2; text-align: justify;">
                                ผู้ใช้งานจะต้องไม่ละเมิดทรัพย์สินทางปัญญาของบริษัทฯ หรือบุคคลอื่นโดยมิได้รับอนุญาต
                                ดังเช่น
                                การบุกรุก (Hack) เข้าสู่
                                บัญชีผู้ใช้งาน (User Account) ของผู้อื่นหรือเข้าสู่เครื่องคอมพิวเตอร์ของบริษัทฯ ,
                                การเขียน
                                เผยแพร่ข้อความใด ๆ ที่
                                ก่อให้เกิดความเสียหายเสื่อมเสียแก่ผู้อื่น , การใช้ภาษาหรือรูปภาพที่ไม่สุภาพ เป็นต้น
                                หากเกิดความเสียหายผู้ใช้งาน
                                จะต้องรับผิดชอบแต่เพียงฝ่ายเดียว
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td
                    style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top; padding-top: 15px;">
                    3.
                </td>
                <td style="line-height: 2; text-align: justify; padding-top: 15px;">
                    ผู้ใช้งานต้องไม่ปฏิบัติการใด ๆ ที่เป็นการขัดต่อกฎหมายว่าด้วยการกระทำความผิดทางคอมพิวเตอร์
                    โดยผู้ใช้งานรับรองว่าหาก
                    มีการกระทำการใด ๆ ดังกล่าว ย่อมถือว่าอยู่นอกเหนือความรับผิดชอบของบริษัทฯ
                </td>
            </tr>
            <tr>
                <td
                    style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top; padding-top: 15px;">
                    4.
                </td>
                <td style="line-height: 2; text-align: justify; padding-top: 15px;">
                    ผู้ใช้งานต้องปฏิบัติตามเงื่อนไข กฎระเบียบ ข้อบังคับที่บริษัทฯ กำหนดไว้ โดยมีผลบังคับใช้ทันที
                    เมื่อมีประกาศจากบริษัทฯ
                </td>
            </tr>
            <tr>
                <td
                    style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top; padding-top: 20px;">
                    5.
                </td>
                <td style="line-height: 2; text-align: justify; padding-top: 20px;">
                    บริษัทฯ ขอสงวนสิทธิ์ในการตรวจสอบเครื่องคอมพิวเตอร์ของผู้ใช้งาน
                    ในกรณีที่พบเห็นการไม่ปฏิบัติตามระเบียบการใช้งาน
                    ระบบเครือข่ายและคอมพิวเตอร์โดยมิต้องแจ้งให้ทราบล่วงหน้า
                </td>
            </tr>
            <tr>
                <td
                    style="width: 25px; text-align: right; padding-right: 15px; vertical-align: top; padding-top: 15px;">
                    6.
                </td>
                <td style="line-height: 2; text-align: justify; padding-top: 15px;">
                    หากผู้ใช้งานฝ่าฝืนหรือไม่ปฏิบัติตามระเบียบการใช้งานระบบเครือข่ายและคอมพิวเตอร์ บริษัทฯ
                    จะพิจารณาบทลงโทษ
                    ทันที
                    ตามความเสียหายที่เกิดขึ้น ดังต่อไปนี้
                    <ul style="list-style-type: disc; margin-top: 15px; padding-left: 25px; line-height: 2;">
                        <li style="font-weight: bold;">ตักเตือนด้วยวาจา</li>
                        <li style="font-weight: bold;">ตักเตือนเป็นลายลักษณ์อักษร</li>
                        <li style="font-weight: bold;">ไม่พิจารณาขึ้นเงินเดือน</li>
                        <li style="font-weight: bold;">เลิกจ้างโดยไม่จ่ายค่าชดเชย</li>
                        <li style="font-weight: bold;">ฟ้องร้องและเรียกค่าเสียหาย</li>
                    </ul>
                </td>
            </tr>
        </table>

        <table style="width: 100%; border: none; margin-top: 100px;">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: center; line-height: 2.5; position: relative;">
                    ลงชื่อ
                    @if($request->user_acknowledged_at && $request->user && $request->user->signature)
                        <img src="{{ asset('storage/signatures/' . $request->user->signature) }}"
                            style="height: 40px; position: absolute; left: 140px; top: -10px;">
                    @endif
                    <span class="dot-line" style="width: 180px;"></span>
                    ผู้ใช้งานรับทราบ<br>
                    (<span class="dot-line" style="width: 180px;">{{ $request->firstname }}
                        {{ $request->lastname }}</span>)<br>
                    วันที่<span class="dot-line"
                        style="width: 100px;">{{ $request->user_acknowledged_at ? $request->user_acknowledged_at->format('d/m/Y') : '......../......../........' }}</span>
                </td>
            </tr>
        </table>
        <div class="doc-no">
            QF-IT-08: Rev: 02: 06-07-20
        </div>
    </div>
</body>

</html>