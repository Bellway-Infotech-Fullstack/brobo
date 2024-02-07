@extends('layouts.admin.app')

@section('title','Settings')
@php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
  $language=\App\Models\BusinessSetting::where('key','language')->first();
  $language = $language->value ?? null;
  $map_api_key_data = \App\Models\BusinessSetting::where('key', 'map_api_key')->first();
  $map_api_key      = (isset($map_api_key_data) && !empty($map_api_key_data)) ? $map_api_key_data->value : '';
  $name=\App\Models\BusinessSetting::where('key','business_name')->first();
  $currency_symbol_position=\App\Models\BusinessSetting::where('key','currency_symbol_position')->first();
  $config=\App\CentralLogics\Helpers::get_business_settings('maintenance_mode');
@endphp
@push('css_or_js')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 23px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }


    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #377dff;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #377dff;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    #location_map_canvas{
        height: 100%;
    }
    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        #location_map_canvas{
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize">{{__('messages.business')}} {{__('messages.setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-md-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body" style="padding-bottom: 12px">
                        <div class="row">
                         
                            <div class="col-6">
                                <h5 class="text-capitalize">
                                    <i class="tio-settings-outlined"></i>
                                    {{__('messages.maintenance_mode')}}
                                </h5>
                            </div>
                            <div class="col-6">
                                <label class="switch ml-3 float-right">
                                    <input type="checkbox" class="status" onclick="maintenance_mode()"
                                        {{isset($config) && $config?'checked':''}}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.update-setup')}}" method="post"
                      enctype="multipart/form-data">
                      @csrf
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">Business Name</label>
                        <input type="text" name="restaurant_name" value="{{$name->value??''}}" class="form-control"
                               placeholder="New Business" required>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            @php
                              $countryData =    \App\Models\BusinessSetting::where('key','country')->first();   
                              $country = (isset($countryData) && !empty($countryData)) ? $countryData->value : '' ;
                            
                              
                            @endphp
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="country">{{__('messages.country')}}</label>
                                <select id="country" name="country" class="form-control  js-select2-custom">
                                    <option value="AF" {{$country == "AF" ? 'selected' : ''}}>Afghanistan</option>
                                    <option value="AX" {{$country == "AX" ? 'selected' : ''}}>Åland Islands</option>
                                    <option value="AL" {{$country == "AL" ? 'selected' : ''}}>Albania</option>
                                    <option value="DZ" {{$country == "DZ" ? 'selected' : ''}}>Algeria</option>
                                    <option value="AS" {{$country == "AS" ? 'selected' : ''}}>American Samoa</option>
                                    <option value="AD" {{$country == "AD" ? 'selected' : ''}}>Andorra</option>
                                    <option value="AO" {{$country == "AO" ? 'selected' : ''}}>Angola</option>
                                    <option value="AI" {{$country == "AI" ? 'selected' : ''}}>Anguilla</option>
                                    <option value="AQ" {{$country == "AQ" ? 'selected' : ''}}>Antarctica</option>
                                    <option value="AG" {{$country == "AG" ? 'selected' : ''}}>Antigua and Barbuda</option>
                                    <option value="AR" {{$country == "AR" ? 'selected' : ''}}>Argentina</option>
                                    <option value="AM" {{$country == "AM" ? 'selected' : ''}}>Armenia</option>
                                    <option value="AW" {{$country == "AW" ? 'selected' : ''}}>Aruba</option>
                                    <option value="AU" {{$country == "AU" ? 'selected' : ''}}>Australia</option>
                                    <option value="AT" {{$country == "AT" ? 'selected' : ''}}>Austria</option>
                                    <option value="AZ" {{$country == "AZ" ? 'selected' : ''}}>Azerbaijan</option>
                                    <option value="BS" {{$country == "BS" ? 'selected' : ''}}>Bahamas</option>
                                    <option value="BH" {{$country == "BH" ? 'selected' : ''}}>Bahrain</option>
                                    <option value="BD" {{$country == "BD" ? 'selected' : ''}}>Bangladesh</option>
                                    <option value="BB" {{$country == "BB" ? 'selected' : ''}}>Barbados</option>
                                    <option value="BY" {{$country == "BY" ? 'selected' : ''}}>Belarus</option>
                                    <option value="BE" {{$country == "BE" ? 'selected' : ''}}>Belgium</option>
                                    <option value="BZ" {{$country == "BZ" ? 'selected' : ''}}>Belize</option>
                                    <option value="BJ" {{$country == "BJ" ? 'selected' : ''}}>Benin</option>
                                    <option value="BM" {{$country == "BM" ? 'selected' : ''}}>Bermuda</option>
                                    <option value="BT" {{$country == "BT" ? 'selected' : ''}}>Bhutan</option>
                                    <option value="BO" {{$country == "BO" ? 'selected' : ''}}>Bolivia, Plurinational State of</option>
                                    <option value="BQ" {{$country == "BQ" ? 'selected' : ''}}>Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA" {{$country == "BA" ? 'selected' : ''}}>Bosnia and Herzegovina</option>
                                    <option value="BW" {{$country == "BW" ? 'selected' : ''}}>Botswana</option>
                                    <option value="BV" {{$country == "BV" ? 'selected' : ''}}>Bouvet Island</option>
                                    <option value="BR" {{$country == "BR" ? 'selected' : ''}}>Brazil</option>
                                    <option value="IO" {{$country == "IO" ? 'selected' : ''}}>British Indian Ocean Territory</option>
                                    <option value="BN" {{$country == "BN" ? 'selected' : ''}}>Brunei Darussalam</option>
                                    <option value="BG" {{$country == "BG" ? 'selected' : ''}}>Bulgaria</option>
                                    <option value="BF" {{$country == "BF" ? 'selected' : ''}}>Burkina Faso</option>
                                    <option value="BI" {{$country == "BI" ? 'selected' : ''}}>Burundi</option>
                                    <option value="KH" {{$country == "KH" ? 'selected' : ''}}>Cambodia</option>
                                    <option value="CM" {{$country == "CM" ? 'selected' : ''}}>Cameroon</option>
                                    <option value="CA" {{$country == "CA" ? 'selected' : ''}}>Canada</option>
                                    <option value="CV" {{$country == "CV" ? 'selected' : ''}}>Cape Verde</option>
                                    <option value="KY" {{$country == "KY" ? 'selected' : ''}}>Cayman Islands</option>
                                    <option value="CF" {{$country == "CF" ? 'selected' : ''}}>Central African Republic</option>
                                    <option value="TD" {{$country == "TD" ? 'selected' : ''}}>Chad</option>
                                    <option value="CL" {{$country == "CL" ? 'selected' : ''}}>Chile</option>
                                    <option value="CN" {{$country == "CN" ? 'selected' : ''}}>China</option>
                                    <option value="CX" {{$country == "CX" ? 'selected' : ''}}>Christmas Island</option>
                                    <option value="CC" {{$country == "CC" ? 'selected' : ''}}>Cocos (Keeling) Islands</option>
                                    <option value="CO" {{$country == "CO" ? 'selected' : ''}}>Colombia</option>
                                    <option value="KM" {{$country == "KM" ? 'selected' : ''}}>Comoros</option>
                                    <option value="CG" {{$country == "CG" ? 'selected' : ''}}>Congo</option>
                                    <option value="CD" {{$country == "CD" ? 'selected' : ''}}>Congo, the Democratic Republic of the</option>
                                    <option value="CK" {{$country == "CK" ? 'selected' : ''}}>Cook Islands</option>
                                    <option value="CR" {{$country == "CR" ? 'selected' : ''}}>Costa Rica</option>
                                    <option value="CI" {{$country == "CI" ? 'selected' : ''}}>Côte d'Ivoire</option>
                                    <option value="HR" {{$country == "HR" ? 'selected' : ''}}>Croatia</option>
                                    <option value="CU" {{$country == "CU" ? 'selected' : ''}}>Cuba</option>
                                    <option value="CW" {{$country == "CW" ? 'selected' : ''}}>Curaçao</option>
                                    <option value="CY" {{$country == "CY" ? 'selected' : ''}}>Cyprus</option>
                                    <option value="CZ" {{$country == "CZ" ? 'selected' : ''}}>Czech Republic</option>
                                    <option value="DK" {{$country == "DK" ? 'selected' : ''}}>Denmark</option>
                                    <option value="DJ" {{$country == "DJ" ? 'selected' : ''}}>Djibouti</option>
                                    <option value="DM" {{$country == "DM" ? 'selected' : ''}}>Dominica</option>
                                    <option value="DO" {{$country == "DO" ? 'selected' : ''}}>Dominican Republic</option>
                                    <option value="EC" {{$country == "EC" ? 'selected' : ''}}>Ecuador</option>
                                    <option value="EG" {{$country == "EG" ? 'selected' : ''}}>Egypt</option>
                                    <option value="SV" {{$country == "SV" ? 'selected' : ''}}>El Salvador</option>
                                    <option value="GQ" {{$country == "GQ" ? 'selected' : ''}}>Equatorial Guinea</option>
                                    <option value="ER" {{$country == "ER" ? 'selected' : ''}}>Eritrea</option>
                                    <option value="EE" {{$country == "EE" ? 'selected' : ''}}>Estonia</option>
                                    <option value="ET" {{$country == "ET" ? 'selected' : ''}}>Ethiopia</option>
                                    <option value="FK" {{$country == "FK" ? 'selected' : ''}}>Falkland Islands (Malvinas)</option>
                                    <option value="FO" {{$country == "FO" ? 'selected' : ''}}>Faroe Islands</option>
                                    <option value="FJ" {{$country == "FJ" ? 'selected' : ''}}>Fiji</option>
                                    <option value="FI" {{$country == "FI" ? 'selected' : ''}}>Finland</option>
                                    <option value="FR" {{$country == "FR" ? 'selected' : ''}}>France</option>
                                    <option value="GF" {{$country == "GF" ? 'selected' : ''}}>French Guiana</option>
                                    <option value="PF" {{$country == "PF" ? 'selected' : ''}}>French Polynesia</option>
                                    <option value="TF" {{$country == "TF" ? 'selected' : ''}}>French Southern Territories</option>
                                    <option value="GA" {{$country == "GA" ? 'selected' : ''}}>Gabon</option>
                                    <option value="GM" {{$country == "GM" ? 'selected' : ''}}>Gambia</option>
                                    <option value="GE" {{$country == "GE" ? 'selected' : ''}}>Georgia</option>
                                    <option value="DE" {{$country == "DE" ? 'selected' : ''}}>Germany</option>
                                    <option value="GH" {{$country == "GH" ? 'selected' : ''}}>Ghana</option>
                                    <option value="GI" {{$country == "GI" ? 'selected' : ''}}>Gibraltar</option>
                                    <option value="GR" {{$country == "GR" ? 'selected' : ''}}>Greece</option>
                                    <option value="GL" {{$country == "GL" ? 'selected' : ''}}>Greenland</option>
                                    <option value="GD" {{$country == "GD" ? 'selected' : ''}}>Grenada</option>
                                    <option value="GP" {{$country == "GP" ? 'selected' : ''}}>Guadeloupe</option>
                                    <option value="GU" {{$country == "GU" ? 'selected' : ''}}>Guam</option>
                                    <option value="GT" {{$country == "GT" ? 'selected' : ''}}>Guatemala</option>
                                    <option value="GG" {{$country == "GG" ? 'selected' : ''}}>Guernsey</option>
                                    <option value="GN" {{$country == "GN" ? 'selected' : ''}}>Guinea</option>
                                    <option value="GW" {{$country == "GW" ? 'selected' : ''}}>Guinea-Bissau</option>
                                    <option value="GY" {{$country == "GY" ? 'selected' : ''}}>Guyana</option>
                                    <option value="HT" {{$country == "HT" ? 'selected' : ''}}>Haiti</option>
                                    <option value="HM" {{$country == "HM" ? 'selected' : ''}}>Heard Island and McDonald Islands</option>
                                    <option value="VA" {{$country == "VA" ? 'selected' : ''}}>Holy See (Vatican City State)</option>
                                    <option value="HN" {{$country == "HN" ? 'selected' : ''}}>Honduras</option>
                                    <option value="HK" {{$country == "HK" ? 'selected' : ''}}>Hong Kong</option>
                                    <option value="HU" {{$country == "HU" ? 'selected' : ''}}>Hungary</option>
                                    <option value="IS" {{$country == "IS" ? 'selected' : ''}}>Iceland</option>
                                    <option value="IN" {{$country == "IN" ? 'selected' : ''}}>India</option>
                                    <option value="ID" {{$country == "ID" ? 'selected' : ''}}>Indonesia</option>
                                    <option value="IR" {{$country == "IR" ? 'selected' : ''}}>Iran, Islamic Republic of</option>
                                    <option value="IQ" {{$country == "IQ" ? 'selected' : ''}}>Iraq</option>
                                    <option value="IE" {{$country == "IE" ? 'selected' : ''}}>Ireland</option>
                                    <option value="IM" {{$country == "IM" ? 'selected' : ''}}>Isle of Man</option>
                                    <option value="IL" {{$country == "IL" ? 'selected' : ''}}>Israel</option>
                                    <option value="IT" {{$country == "IT" ? 'selected' : ''}}>Italy</option>
                                    <option value="JM" {{$country == "JM" ? 'selected' : ''}}>Jamaica</option>
                                    <option value="JP" {{$country == "JP" ? 'selected' : ''}}>Japan</option>
                                    <option value="JE" {{$country == "JE" ? 'selected' : ''}}>Jersey</option>
                                    <option value="JO" {{$country == "JO" ? 'selected' : ''}}>Jordan</option>
                                    <option value="KZ" {{$country == "KZ" ? 'selected' : ''}}>Kazakhstan</option>
                                    <option value="KE" {{$country == "KE" ? 'selected' : ''}}>Kenya</option>
                                    <option value="KI" {{$country == "KI" ? 'selected' : ''}}>Kiribati</option>
                                    <option value="KP" {{$country == "KP" ? 'selected' : ''}}>Korea, Democratic People's Republic of</option>
                                    <option value="KR" {{$country == "KR" ? 'selected' : ''}}>Korea, Republic of</option>
                                    <option value="KW" {{$country == "KW" ? 'selected' : ''}}>Kuwait</option>
                                    <option value="KG" {{$country == "KG" ? 'selected' : ''}}>Kyrgyzstan</option>
                                    <option value="LA" {{$country == "LA" ? 'selected' : ''}}>Lao People's Democratic Republic</option>
                                    <option value="LV" {{$country == "LV" ? 'selected' : ''}}>Latvia</option>
                                    <option value="LB" {{$country == "LB" ? 'selected' : ''}}>Lebanon</option>
                                    <option value="LS" {{$country == "LS" ? 'selected' : ''}}>Lesotho</option>
                                    <option value="LR" {{$country == "LR" ? 'selected' : ''}}>Liberia</option>
                                    <option value="LY" {{$country == "LY" ? 'selected' : ''}}>Libya</option>
                                    <option value="LI" {{$country == "LI" ? 'selected' : ''}}>Liechtenstein</option>
                                    <option value="LT" {{$country == "LT" ? 'selected' : ''}}>Lithuania</option>
                                    <option value="LU" {{$country == "LU" ? 'selected' : ''}}>Luxembourg</option>
                                    <option value="MO" {{$country == "MO" ? 'selected' : ''}}>Macao</option>
                                    <option value="MK" {{$country == "MK" ? 'selected' : ''}}>Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG" {{$country == "MG" ? 'selected' : ''}}>Madagascar</option>
                                    <option value="MW" {{$country == "MW" ? 'selected' : ''}}>Malawi</option>
                                    <option value="MY" {{$country == "MY" ? 'selected' : ''}}>Malaysia</option>
                                    <option value="MV" {{$country == "MV" ? 'selected' : ''}}>Maldives</option>
                                    <option value="ML" {{$country == "ML" ? 'selected' : ''}}>Mali</option>
                                    <option value="MT" {{$country == "MT" ? 'selected' : ''}}>Malta</option>
                                    <option value="MH" {{$country == "MH" ? 'selected' : ''}}>Marshall Islands</option>
                                    <option value="MQ" {{$country == "MQ" ? 'selected' : ''}}>Martinique</option>
                                    <option value="MR" {{$country == "MR" ? 'selected' : ''}}>Mauritania</option>
                                    <option value="MU" {{$country == "MU" ? 'selected' : ''}}>Mauritius</option>
                                    <option value="YT" {{$country == "YT" ? 'selected' : ''}}>Mayotte</option>
                                    <option value="MX" {{$country == "MX" ? 'selected' : ''}}>Mexico</option>
                                    <option value="FM" {{$country == "FM" ? 'selected' : ''}}>Micronesia, Federated States of</option>
                                    <option value="MD" {{$country == "MD" ? 'selected' : ''}}>Moldova, Republic of</option>
                                    <option value="MC" {{$country == "MC" ? 'selected' : ''}}>Monaco</option>
                                    <option value="MN" {{$country == "MN" ? 'selected' : ''}}>Mongolia</option>
                                    <option value="ME" {{$country == "ME" ? 'selected' : ''}}>Montenegro</option>
                                    <option value="MS" {{$country == "MS" ? 'selected' : ''}}>Montserrat</option>
                                    <option value="MA" {{$country == "MA" ? 'selected' : ''}}>Morocco</option>
                                    <option value="MZ" {{$country == "MZ" ? 'selected' : ''}}>Mozambique</option>
                                    <option value="MM" {{$country == "MM" ? 'selected' : ''}}>Myanmar</option>
                                    <option value="NA" {{$country == "NA" ? 'selected' : ''}}>Namibia</option>
                                    <option value="NR" {{$country == "NR" ? 'selected' : ''}}>Nauru</option>
                                    <option value="NP" {{$country == "NP" ? 'selected' : ''}}>Nepal</option>
                                    <option value="NL" {{$country == "NL" ? 'selected' : ''}}>Netherlands</option>
                                    <option value="NC" {{$country == "NC" ? 'selected' : ''}}>New Caledonia</option>
                                    <option value="NZ" {{$country == "NZ" ? 'selected' : ''}}>New Zealand</option>
                                    <option value="NI" {{$country == "NI" ? 'selected' : ''}}>Nicaragua</option>
                                    <option value="NE" {{$country == "NE" ? 'selected' : ''}}>Niger</option>
                                    <option value="NG" {{$country == "NG" ? 'selected' : ''}}>Nigeria</option>
                                    <option value="NU" {{$country == "NU" ? 'selected' : ''}}>Niue</option>
                                    <option value="NF" {{$country == "NF" ? 'selected' : ''}}>Norfolk Island</option>
                                    <option value="MP" {{$country == "MP" ? 'selected' : ''}}>Northern Mariana Islands</option>
                                    <option value="NO" {{$country == "NO" ? 'selected' : ''}}>Norway</option>
                                    <option value="OM" {{$country == "OM" ? 'selected' : ''}}>Oman</option>
                                    <option value="PK" {{$country == "PK" ? 'selected' : ''}}>Pakistan</option>
                                    <option value="PW" {{$country == "PW" ? 'selected' : ''}}>Palau</option>
                                    <option value="PS" {{$country == "PS" ? 'selected' : ''}}>Palestinian Territory, Occupied</option>
                                    <option value="PA" {{$country == "PA" ? 'selected' : ''}}>Panama</option>
                                    <option value="PG" {{$country == "PG" ? 'selected' : ''}}>Papua New Guinea</option>
                                    <option value="PY" {{$country == "PY" ? 'selected' : ''}}>Paraguay</option>
                                    <option value="PE" {{$country == "PE" ? 'selected' : ''}}>Peru</option>
                                    <option value="PH" {{$country == "PH" ? 'selected' : ''}}>Philippines</option>
                                    <option value="PN" {{$country == "PN" ? 'selected' : ''}}>Pitcairn</option>
                                    <option value="PL" {{$country == "PL" ? 'selected' : ''}}>Poland</option>
                                    <option value="PT" {{$country == "PT" ? 'selected' : ''}}>Portugal</option>
                                    <option value="PR" {{$country == "PR" ? 'selected' : ''}}>Puerto Rico</option>
                                    <option value="QA" {{$country == "QA" ? 'selected' : ''}}>Qatar</option>
                                    <option value="RE" {{$country == "RE" ? 'selected' : ''}}>Réunion</option>
                                    <option value="RO" {{$country == "RO" ? 'selected' : ''}}>Romania</option>
                                    <option value="RU" {{$country == "RU" ? 'selected' : ''}}>Russian Federation</option>
                                    <option value="RW" {{$country == "RW" ? 'selected' : ''}}>Rwanda</option>
                                    <option value="BL" {{$country == "BL" ? 'selected' : ''}}>Saint Barthélemy</option>
                                    <option value="SH" {{$country == "SH" ? 'selected' : ''}}>Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN" {{$country == "KN" ? 'selected' : ''}}>Saint Kitts and Nevis</option>
                                    <option value="LC" {{$country == "LC" ? 'selected' : ''}}>Saint Lucia</option>
                                    <option value="MF" {{$country == "MF" ? 'selected' : ''}}>Saint Martin (French part)</option>
                                    <option value="PM" {{$country == "PM" ? 'selected' : ''}}>Saint Pierre and Miquelon</option>
                                    <option value="VC" {{$country == "VC" ? 'selected' : ''}}>Saint Vincent and the Grenadines</option>
                                    <option value="WS" {{$country == "WS" ? 'selected' : ''}}>Samoa</option>
                                    <option value="SM" {{$country == "SM" ? 'selected' : ''}}>San Marino</option>
                                    <option value="ST" {{$country == "ST" ? 'selected' : ''}}>Sao Tome and Principe</option>
                                    <option value="SA" {{$country == "SA" ? 'selected' : ''}}>Saudi Arabia</option>
                                    <option value="SN" {{$country == "SN" ? 'selected' : ''}}>Senegal</option>
                                    <option value="RS" {{$country == "RS" ? 'selected' : ''}}>Serbia</option>
                                    <option value="SC" {{$country == "SC" ? 'selected' : ''}}>Seychelles</option>
                                    <option value="SL" {{$country == "SL" ? 'selected' : ''}}>Sierra Leone</option>
                                    <option value="SG" {{$country == "SG" ? 'selected' : ''}}>Singapore</option>
                                    <option value="SX" {{$country == "SX" ? 'selected' : ''}}>Sint Maarten (Dutch part)</option>
                                    <option value="SK" {{$country == "SK" ? 'selected' : ''}}>Slovakia</option>
                                    <option value="SI" {{$country == "SI" ? 'selected' : ''}}>Slovenia</option>
                                    <option value="SB" {{$country == "SB" ? 'selected' : ''}}>Solomon Islands</option>
                                    <option value="SO" {{$country == "SO" ? 'selected' : ''}}>Somalia</option>
                                    <option value="ZA" {{$country == "ZA" ? 'selected' : ''}}>South Africa</option>
                                    <option value="GS" {{$country == "GS" ? 'selected' : ''}}>South Georgia and the South Sandwich Islands</option>
                                    <option value="SS" {{$country == "SS" ? 'selected' : ''}}>South Sudan</option>
                                    <option value="ES" {{$country == "ES" ? 'selected' : ''}}>Spain</option>
                                    <option value="LK" {{$country == "LK" ? 'selected' : ''}}>Sri Lanka</option>
                                    <option value="SD" {{$country == "SD" ? 'selected' : ''}}>Sudan</option>
                                    <option value="SR" {{$country == "SR" ? 'selected' : ''}}>Suriname</option>
                                    <option value="SJ" {{$country == "SJ" ? 'selected' : ''}}>Svalbard and Jan Mayen</option>
                                    <option value="SZ" {{$country == "SZ" ? 'selected' : ''}}>Swaziland</option>
                                    <option value="SE" {{$country == "SE" ? 'selected' : ''}}>Sweden</option>
                                    <option value="CH" {{$country == "CH" ? 'selected' : ''}}>Switzerland</option>
                                    <option value="SY" {{$country == "SY" ? 'selected' : ''}}>Syrian Arab Republic</option>
                                    <option value="TW" {{$country == "TW" ? 'selected' : ''}}>Taiwan, Province of China</option>
                                    <option value="TJ" {{$country == "TJ" ? 'selected' : ''}}>Tajikistan</option>
                                    <option value="TZ" {{$country == "TZ" ? 'selected' : ''}}>Tanzania, United Republic of</option>
                                    <option value="TH" {{$country == "TH" ? 'selected' : ''}}>Thailand</option>
                                    <option value="TL" {{$country == "TL" ? 'selected' : ''}}>Timor-Leste</option>
                                    <option value="TG" {{$country == "TG" ? 'selected' : ''}}>Togo</option>
                                    <option value="TK" {{$country == "TK" ? 'selected' : ''}}>Tokelau</option>
                                    <option value="TO" {{$country == "TO" ? 'selected' : ''}}>Tonga</option>
                                    <option value="TT" {{$country == "TT" ? 'selected' : ''}}>Trinidad and Tobago</option>
                                    <option value="TN" {{$country == "TN" ? 'selected' : ''}}>Tunisia</option>
                                    <option value="TR" {{$country == "TR" ? 'selected' : ''}}>Turkey</option>
                                    <option value="TM" {{$country == "TM" ? 'selected' : ''}}>Turkmenistan</option>
                                    <option value="TC" {{$country == "TC" ? 'selected' : ''}}>Turks and Caicos Islands</option>
                                    <option value="TV" {{$country == "TV" ? 'selected' : ''}}>Tuvalu</option>
                                    <option value="UG" {{$country == "UG" ? 'selected' : ''}}>Uganda</option>
                                    <option value="UA" {{$country == "UA" ? 'selected' : ''}}>Ukraine</option>
                                    <option value="AE" {{$country == "AE" ? 'selected' : ''}}>United Arab Emirates</option>
                                    <option value="GB" {{$country == "GB" ? 'selected' : ''}}>United Kingdom</option>
                                    <option value="US" {{$country == "US" ? 'selected' : ''}}>United States</option>
                                    <option value="UM" {{$country == "UM" ? 'selected' : ''}}>United States Minor Outlying Islands</option>
                                    <option value="UY" {{$country == "UY" ? 'selected' : ''}}>Uruguay</option>
                                    <option value="UZ" {{$country == "UZ" ? 'selected' : ''}}>Uzbekistan</option>
                                    <option value="VU" {{$country == "VU" ? 'selected' : ''}}>Vanuatu</option>
                                    <option value="VE" {{$country == "VE" ? 'selected' : ''}}>Venezuela, Bolivarian Republic of</option>
                                    <option value="VN" {{$country == "VN" ? 'selected' : ''}}>Viet Nam</option>
                                    <option value="VG" {{$country == "VG" ? 'selected' : ''}}>Virgin Islands, British</option>
                                    <option value="VI" {{$country == "VI" ? 'selected' : ''}}>Virgin Islands, U.S.</option>
                                    <option value="WF" {{$country == "WF" ? 'selected' : ''}}>Wallis and Futuna</option>
                                    <option value="EH" {{$country == "EH" ? 'selected' : ''}}>Western Sahara</option>
                                    <option value="YE" {{$country == "YE" ? 'selected' : ''}}>Yemen</option>
                                    <option value="ZM" {{$country == "ZM" ? 'selected' : ''}}>Zambia</option>
                                    <option value="ZW" {{$country == "ZW" ? 'selected' : ''}}>Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                 
                        <div class="col-md-4 col-sm-6 col-12">
                            @php($tz=\App\Models\BusinessSetting::where('key','timezone')->first())
                            @php($tz=$tz?$tz->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize">{{__('messages.time_zone')}}</label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    <option value="UTC" {{$tz?($tz==''?'selected':''):''}}>UTC</option>
                                    <option value="Etc/GMT+12"  {{$tz?($tz=='Etc/GMT+12'?'selected':''):''}}>(GMT-12:00) International Date Line West</option>
                                    <option value="Pacific/Midway"  {{$tz?($tz=='Pacific/Midway'?'selected':''):''}}>(GMT-11:00) Midway Island, Samoa</option>
                                    <option value="Pacific/Honolulu"  {{$tz?($tz=='Pacific/Honolulu'?'selected':''):''}}>(GMT-10:00) Hawaii</option>
                                    <option value="US/Alaska"  {{$tz?($tz=='US/Alaska'?'selected':''):''}}>(GMT-09:00) Alaska</option>
                                    <option value="America/Los_Angeles"  {{$tz?($tz=='America/Los_Angeles'?'selected':''):''}}>(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value="America/Tijuana"  {{$tz?($tz=='America/Tijuana'?'selected':''):''}}>(GMT-08:00) Tijuana, Baja California</option>
                                    <option value="US/Arizona"  {{$tz?($tz=='US/Arizona'?'selected':''):''}}>(GMT-07:00) Arizona</option>
                                    <option value="America/Chihuahua"  {{$tz?($tz=='America/Chihuahua'?'selected':''):''}}>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="US/Mountain"  {{$tz?($tz=='US/Mountain'?'selected':''):''}}>(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option value="America/Managua"  {{$tz?($tz=='America/Managua'?'selected':''):''}}>(GMT-06:00) Central America</option>
                                    <option value="US/Central"  {{$tz?($tz=='US/Central'?'selected':''):''}}>(GMT-06:00) Central Time (US & Canada)</option>
                                    <option value="America/Mexico_City"  {{$tz?($tz=='America/Mexico_City'?'selected':''):''}}>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value="Canada/Saskatchewan"  {{$tz?($tz=='Canada/Saskatchewan'?'selected':''):''}}>(GMT-06:00) Saskatchewan</option>
                                    <option value="America/Bogota"  {{$tz?($tz=='America/Bogota'?'selected':''):''}}>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option value="US/Eastern"  {{$tz?($tz=='US/Eastern'?'selected':''):''}}>(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option value="US/East-Indiana"  {{$tz?($tz=='US/East-Indiana'?'selected':''):''}}>(GMT-05:00) Indiana (East)</option>
                                    <option value="Canada/Atlantic"  {{$tz?($tz=='Canada/Atlantic'?'selected':''):''}}>(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option value="America/Caracas"  {{$tz?($tz=='America/Caracas'?'selected':''):''}}>(GMT-04:00) Caracas, La Paz</option>
                                    <option value="America/Manaus"  {{$tz?($tz=='America/Manaus'?'selected':''):''}}>(GMT-04:00) Manaus</option>
                                    <option value="America/Santiago"  {{$tz?($tz=='America/Santiago'?'selected':''):''}}>(GMT-04:00) Santiago</option>
                                    <option value="Canada/Newfoundland"  {{$tz?($tz=='Canada/Newfoundland'?'selected':''):''}}>(GMT-03:30) Newfoundland</option>
                                    <option value="America/Sao_Paulo"  {{$tz?($tz=='America/Sao_Paulo'?'selected':''):''}}>(GMT-03:00) Brasilia</option>
                                    <option value="America/Argentina/Buenos_Aires"  {{$tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''}}>(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option value="America/Godthab"  {{$tz?($tz=='America/Godthab'?'selected':''):''}}>(GMT-03:00) Greenland</option>
                                    <option value="America/Montevideo"  {{$tz?($tz=='America/Montevideo'?'selected':''):''}}>(GMT-03:00) Montevideo</option>
                                    <option value="America/Noronha"  {{$tz?($tz=='America/Noronha'?'selected':''):''}}>(GMT-02:00) Mid-Atlantic</option>
                                    <option value="Atlantic/Cape_Verde"  {{$tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''}}>(GMT-01:00) Cape Verde Is.</option>
                                    <option value="Atlantic/Azores"  {{$tz?($tz=='Atlantic/Azores'?'selected':''):''}}>(GMT-01:00) Azores</option>
                                    <option value="Africa/Casablanca"  {{$tz?($tz=='Africa/Casablanca'?'selected':''):''}}>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option value="Etc/Greenwich"  {{$tz?($tz=='Etc/Greenwich'?'selected':''):''}}>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option value="Europe/Amsterdam"  {{$tz?($tz=='Europe/Amsterdam'?'selected':''):''}}>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value="Europe/Belgrade"  {{$tz?($tz=='Europe/Belgrade'?'selected':''):''}}>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value="Europe/Brussels"  {{$tz?($tz=='Europe/Brussels'?'selected':''):''}}>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="Europe/Sarajevo"  {{$tz?($tz=='Europe/Sarajevo'?'selected':''):''}}>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value="Africa/Lagos"  {{$tz?($tz=='Africa/Lagos'?'selected':''):''}}>(GMT+01:00) West Central Africa</option>
                                    <option value="Asia/Amman"  {{$tz?($tz=='Asia/Amman'?'selected':''):''}}>(GMT+02:00) Amman</option>
                                    <option value="Europe/Athens"  {{$tz?($tz=='Europe/Athens'?'selected':''):''}}>(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option value="Asia/Beirut"  {{$tz?($tz=='Asia/Beirut'?'selected':''):''}}>(GMT+02:00) Beirut</option>
                                    <option value="Africa/Cairo"  {{$tz?($tz=='Africa/Cairo'?'selected':''):''}}>(GMT+02:00) Cairo</option>
                                    <option value="Africa/Harare"  {{$tz?($tz=='Africa/Harare'?'selected':''):''}}>(GMT+02:00) Harare, Pretoria</option>
                                    <option value="Europe/Helsinki"  {{$tz?($tz=='Europe/Helsinki'?'selected':''):''}}>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value="Asia/Jerusalem"  {{$tz?($tz=='Asia/Jerusalem'?'selected':''):''}}>(GMT+02:00) Jerusalem</option>
                                    <option value="Europe/Minsk"  {{$tz?($tz=='Europe/Minsk'?'selected':''):''}}>(GMT+02:00) Minsk</option>
                                    <option value="Africa/Windhoek"  {{$tz?($tz=='Africa/Windhoek'?'selected':''):''}}>(GMT+02:00) Windhoek</option>
                                    <option value="Asia/Kuwait"  {{$tz?($tz=='Asia/Kuwait'?'selected':''):''}}>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option value="Europe/Moscow"  {{$tz?($tz=='Europe/Moscow'?'selected':''):''}}>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option value="Africa/Nairobi"  {{$tz?($tz=='Africa/Nairobi'?'selected':''):''}}>(GMT+03:00) Nairobi</option>
                                    <option value="Asia/Tbilisi"  {{$tz?($tz=='Asia/Tbilisi'?'selected':''):''}}>(GMT+03:00) Tbilisi</option>
                                    <option value="Asia/Tehran"  {{$tz?($tz=='Asia/Tehran'?'selected':''):''}}>(GMT+03:30) Tehran</option>
                                    <option value="Asia/Muscat"  {{$tz?($tz=='Asia/Muscat'?'selected':''):''}}>(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option value="Asia/Baku"  {{$tz?($tz=='Asia/Baku'?'selected':''):''}}>(GMT+04:00) Baku</option>
                                    <option value="Asia/Yerevan"  {{$tz?($tz=='Asia/Yerevan'?'selected':''):''}}>(GMT+04:00) Yerevan</option>
                                    <option value="Asia/Kabul"  {{$tz?($tz=='Asia/Kabul'?'selected':''):''}}>(GMT+04:30) Kabul</option>
                                    <option value="Asia/Yekaterinburg"  {{$tz?($tz=='Asia/Yekaterinburg'?'selected':''):''}}>(GMT+05:00) Yekaterinburg</option>
                                    <option value="Asia/Karachi"  {{$tz?($tz=='Asia/Karachi'?'selected':''):''}}>(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <!-- <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                    <option value="Asia/Katmandu"  {{$tz?($tz=='Asia/Katmandu'?'selected':''):''}}>(GMT+05:45) Kathmandu</option>
                                    <option value="Asia/Almaty"  {{$tz?($tz=='Asia/Almaty'?'selected':''):''}}>(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option value="Asia/Dhaka"  {{$tz?($tz=='Asia/Dhaka'?'selected':''):''}}>(GMT+06:00) Astana, Dhaka</option>
                                    <option value="Asia/Rangoon"  {{$tz?($tz=='Asia/Rangoon'?'selected':''):''}}>(GMT+06:30) Yangon (Rangoon)</option>
                                    <option value="Asia/Bangkok"  {{$tz?($tz=='"Asia/Bangkok'?'selected':''):''}}>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="Asia/Krasnoyarsk"  {{$tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''}}>(GMT+07:00) Krasnoyarsk</option>
                                    <option value="Asia/Hong_Kong"  {{$tz?($tz=='Asia/Hong_Kong'?'selected':''):''}}>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value="Asia/Kuala_Lumpur"  {{$tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''}}>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option value="Asia/Irkutsk"  {{$tz?($tz=='Asia/Irkutsk'?'selected':''):''}}>(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option value="Australia/Perth"  {{$tz?($tz=='Australia/Perth'?'selected':''):''}}>(GMT+08:00) Perth</option>
                                    <option value="Asia/Taipei"  {{$tz?($tz=='Asia/Taipei'?'selected':''):''}}>(GMT+08:00) Taipei</option>
                                    <option value="Asia/Tokyo"  {{$tz?($tz=='Asia/Tokyo'?'selected':''):''}}>(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value="Asia/Seoul"  {{$tz?($tz=='Asia/Seoul'?'selected':''):''}}>(GMT+09:00) Seoul</option>
                                    <option value="Asia/Yakutsk"  {{$tz?($tz=='Asia/Yakutsk'?'selected':''):''}}>(GMT+09:00) Yakutsk</option>
                                    <option value="Australia/Adelaide"  {{$tz?($tz=='Australia/Adelaide'?'selected':''):''}}>(GMT+09:30) Adelaide</option>
                                    <option value="Australia/Darwin"  {{$tz?($tz=='Australia/Darwin'?'selected':''):''}}>(GMT+09:30) Darwin</option>
                                    <option value="Australia/Brisbane"  {{$tz?($tz=='Australia/Brisbane'?'selected':''):''}}>(GMT+10:00) Brisbane</option>
                                    <option value="Australia/Canberra"  {{$tz?($tz=='Australia/Canberra'?'selected':''):''}}>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value="Australia/Hobart"  {{$tz?($tz=='Australia/Hobart'?'selected':''):''}}>(GMT+10:00) Hobart</option>
                                    <option value="Pacific/Guam"  {{$tz?($tz=='Pacific/Guam'?'selected':''):''}}>(GMT+10:00) Guam, Port Moresby</option>
                                    <option value="Asia/Vladivostok"  {{$tz?($tz=='Asia/Vladivostok'?'selected':''):''}}>(GMT+10:00) Vladivostok</option>
                                    <option value="Asia/Magadan"  {{$tz?($tz=='Asia/Magadan'?'selected':''):''}}>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option value="Pacific/Auckland"  {{$tz?($tz=='Pacific/Auckland'?'selected':''):''}}>(GMT+12:00) Auckland, Wellington</option>
                                    <option value="Pacific/Fiji"  {{$tz?($tz=='Pacific/Fiji'?'selected':''):''}}>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option value="Pacific/Tongatapu"  {{$tz?($tz=='Pacific/Tongatapu'?'selected':''):''}}>(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            @php($tf=\App\Models\BusinessSetting::where('key','timeformat')->first())
                            @php($tf=$tf?$tf->value:'24')
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize">{{__('messages.time_format')}}</label>
                                <select name="time_format" class="form-control">
                                    <option value="12" {{$tf=='12'?'selected':''}}>{{__('messages.12_hour')}}</option>
                                    <option value="24" {{$tf=='24'?'selected':''}}>{{__('messages.24_hour')}}</option>
                                </select>
                            </div>

                        </div>
                    </div>
                   

                  

                    <div class="row">
                        @php($phone=\App\Models\BusinessSetting::where('key','phone')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.phone')}}</label>
                                <input type="text" value="{{$phone->value??''}}"
                                       name="phone" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        @php($email=\App\Models\BusinessSetting::where('key','email_address')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.email')}}</label>
                                <input type="email" value="{{$email->value??''}}"
                                       name="email" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @php($mininum_order_amount=\App\Models\BusinessSetting::where('key','mininum_order_amount')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Mininum Order Amount</label>
                                <input type="text" value="{{$mininum_order_amount->value??''}}"
                                       name="mininum_order_amount" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        @php($order_installment_percent_1=\App\Models\BusinessSetting::where('key','order_installment_percent_1')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 1</label>
                                <input type="text" value="{{$order_installment_percent_1->value??''}}"
                                       name="order_installment_percent_1" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        @php($order_installment_percent_2=\App\Models\BusinessSetting::where('key','order_installment_percent_2')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 2</label>
                                <input type="text" value="{{$order_installment_percent_2->value??''}}"
                                       name="order_installment_percent_2" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                        @php($order_installment_percent_3=\App\Models\BusinessSetting::where('key','order_installment_percent_3')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 3</label>
                                <input type="text" value="{{$order_installment_percent_3->value??''}}"
                                       name="order_installment_percent_3" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      
                        
                        @php($gst_percent=\App\Models\BusinessSetting::where('key','gst_percent')->first())
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">GST for rented items (%)</label>
                                <input type="text" value="{{$gst_percent->value??''}}"
                                       name="gst_percent" class="form-control" placeholder="">
                            </div>
                        </div>
                        @php($referred_discount=\App\Models\BusinessSetting::where('key','referred_discount')->first())
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Referred Discount (%)</label>
                                <input type="text" value="{{$referred_discount->value??''}}"
                                       name="referred_discount" class="form-control" placeholder="" required>
                            </div>
                        </div>

                        @php($refunded_amount=\App\Models\BusinessSetting::where('key','refunded_amount')->first())
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Refunded Amount (%)</label>
                                <input type="text" value="{{$refunded_amount->value??''}}"
                                       name="refunded_amount" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delivery_charge_slab_data = \App\Models\BusinessSetting::where('key','delivery_charge_slabs')->first();
                    $delivery_charge_slab_data = (isset($delivery_charge_slab_data) && !empty($delivery_charge_slab_data)) ? explode(",",$delivery_charge_slab_data->value) : array();

                    ?>

                    <div id="delivery_charge_section">
                        <input type="hidden" id="delivery_charge_section_count_length"  value="{{count($delivery_charge_slab_data)}}">

                    <?php
                    if(isset($delivery_charge_slab_data) && !empty($delivery_charge_slab_data)){
                           foreach($delivery_charge_slab_data as $key => $value){
                                $delivery_charge_slab = explode("-",$value);
                                
                                $delivery_charge = $delivery_charge_slab[0];
                                $min_amount = $delivery_charge_slab[1];
                                $max_amount   = $delivery_charge_slab[2];

                        ?>
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Min. Amount</label>
                                    <input type="text"  name="min_amount[]" class="form-control" value="{{ $min_amount ?? ''}}">
                                </div>                         
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Max. Amount</label>
                                    <input type="text"  name="max_amount[]" class="form-control" value="{{ $max_amount ?? ''}}">
                                </div>                           
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Delivery Charge</label>
                                    <input type="text" 
                                        name="delivery_charge[]" class="form-control" placeholder="" value="{{ $delivery_charge ?? ''}}">
                                </div>
                            </div>
                            @if($key > 0)
                            <div  style="float:right;">
                                <?php
                                 
                                ?>
                                <a href="javascript:void(0)" class="remove-delivery-charge-slab remove-dynamic-delivery-charge-slab" data-min-amount="{{ $min_amount ?? '' }}" data-count="{{$key+1}}"> Remove </a>
                            </div> 
                            @endif  
                        </div>
                    <?php }} else { ?>
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Min. Amount</label>
                                    <input type="text"  name="min_amount[]" class="form-control">
                                </div>                         
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Max. Amount</label>
                                    <input type="text"  name="max_amount[]" class="form-control">
                                </div>                           
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="input-label d-inline" for="exampleFormControlInput1">Delivery Charge</label>
                                    <input type="text" 
                                        name="delivery_charge[]" class="form-control" placeholder="">
                                </div>
                            </div>
                            
                        </div>

                    <?php } ?>
             
                    </div>
                            <div>
                             <a href="javascript:void(0)" class="add-more-section"> Add More </a>
                    </div>
                    
                    
                  <?php

                        $order_time_slot_data = \App\Models\BusinessSetting::where('key','order_time_slots')->first();  
                        
                            $order_time_slot_data = (isset($order_time_slot_data) && !empty($order_time_slot_data)) ? explode(",",$order_time_slot_data->value) : array();

                    

                    ?>
                    <input type="hidden" id="time_slot_length"  value="{{count($order_time_slot_data)}}">

                    <div  id="time_slot_data">
                        
                            <?php
     
                         if(isset($order_time_slot_data) && !empty($order_time_slot_data)){
                                    
                            foreach($order_time_slot_data as $key => $value){
                                $time_slot_data = explode("-",$value);
                                $from_time_slot = $time_slot_data[0];
                                $to_time_slot   = $time_slot_data[1];
                                $time_slot =   $from_time_slot . "-" . $to_time_slot;
                    
                            ?>

                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="input-label d-inline" for="exampleFormControlInput1">Order From Time Slot {{$key+1}} </label>
                                            <input type="time" value="{{ $from_time_slot ?? ''}}" name="order_from_time_slots[]" class="form-control">
                                        </div>                         
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="input-label d-inline" for="exampleFormControlInput1">Order To Time Slot {{$key+1}}</label>
                                            <input type="time" value="{{ $to_time_slot ?? ''}}" name="order_to_time_slots[]" class="form-control">
                                        </div>
                                        @if($key > 0)
                                        <div  style="float:right;">
                                            <?php
                                             
                                            ?>
                                            <a href="javascript:void(0)" class="remove-time-slot remove-dynamic-time-slot" data-time-slot="{{ $time_slot }}" data-count="{{$key+1}}"> Remove </a>
                                        </div> 
                                        @endif                            
                                    </div>
                                      

                                </div>
                         
                            
                            <?php }} else { ?>
                              <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-inline" for="exampleFormControlInput1">Order From Time Slot 1</label>
                                        <input type="time"  name="order_from_time_slots[]" class="form-control">
                                    </div>                         
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-inline" for="exampleFormControlInput1">Order To Time Slot 1</label>
                                        <input type="time"  name="order_to_time_slots[]" class="form-control">
                                    </div>                           
                                </div>
                                 </div>


                            <?php } ?>
                            
                        
                    </div>
               
                    <div  style='float:right;'>
                         <a href="javascript:void(0)" class="add-more-time-slot"> Add More </a>
                    </div>

                    <div class="row" style="display:none">
                        <div class="col-sm-6">
                            @php($address=\App\Models\BusinessSetting::where('key','address')->first())
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.address')}}</label>
                                <textarea id="address" name="address" class="form-control" placeholder="" rows="1" required>{{$address->value??''}}</textarea>
                            </div>
                            @php($default_location=\App\Models\BusinessSetting::where('key','default_location')->first())
                            <?php
                                $default_location = '';
                                if(isset($default_location) && !empty($default_location)){
                                    $default_location = $default_location->value?json_decode($default_location->value, true):0;
                                }
                            ?>
                             

                           
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="latitude">{{__('messages.latitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.click_on_the_map_select_your_defaul_location')}}"><img src="{{asset($assetPrefixPath.'/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.click_on_the_map_select_your_defaul_location')}}"></span></label>
                                <input type="text" id="latitude"
                                       name="latitude" class="form-control d-inline"
                                       placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                            </div>
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="longitude">{{__('messages.longitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.click_on_the_map_select_your_defaul_location')}}"><img src="{{asset($assetPrefixPath.'/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.click_on_the_map_select_your_defaul_location')}}"></span></label>
                                <input type="text"
                                       name="longitude" class="form-control"
                                       placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input id="pac-input" class="controls rounded" style="height: 3em;width:fit-content;" title="{{__('messages.search_your_location_here')}}" type="text" placeholder="{{__('messages.search_here')}}"/>
                            <div id="location_map_canvas"></div>
                        </div>
                    </div>

                     <div class="row">
                        <div class="form-group col-12" style="margin-left: -15px;">
                                        @php($footer_text=\App\Models\BusinessSetting::where('key','footer_text')->first())
                            <label class="input-label d-inline" for="exampleFormControlInput1">Footer
                                Text</label>
                            <textarea  name="footer_text" class="form-control"  placeholder="" required="">{{  $footer_text->value??''  }}</textarea>
                        </div>
                    </div>


                
                    <div class="row">
                        <div class="col-sm-6">
                                @php($logo=\App\Models\BusinessSetting::where('key','logo')->first())
                    @php($logo=$logo->value??'')
                    <div class="form-group">
                        <label class="input-label d-inline">{{__('messages.logo')}}</label><small style="color: red">* ( {{__('messages.ratio')}} 3:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                        </div>
                        <hr>
                        <?php
                
                        $logoPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $logo) : asset('storage/app/public/business/' . $logo);        
                    ?>
                        <center>
                            <img style="height: 100px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 src="{{$logoPath}}" alt="logo image"/>
                        </center>
                    </div>
                        </div>
                        <div class="col-sm-6">
                    @php($favIconData = \App\Models\BusinessSetting::where('key','fav_icon')->first())
                    @php($favIcon =$favIconData->value??'')
                    <div class="form-group">
                        <label class="input-label d-inline">Fav Icon</label><small style="color: red">* ( {{__('messages.ratio')}} 1:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="fav_icon" id="customFileEg2" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg2">{{__('messages.choose')}} {{__('messages.file')}}</label>
                        </div>
                        <hr>
                        <?php
                
                        $favIconPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $favIcon) : asset('storage/app/public/business/' . $favIcon);        
                    ?>
                    
                   
                        <center>
                           <img style="height: 100px; border: 1px solid; border-radius: 10px;" id="viewer2"
     src="{{$favIconPath}}" alt="fav icon" class="{{ $favIcon ? 'd-block' : 'd-none' }}">

                        </center>
                       
                    </div>
                        </div>
                    </div>
                    <hr>
                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary mb-2">{{trans('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
    
           var delivery_charge_section_count = 1;

           if($("#delivery_charge_section_count_length").val() > 0){
                var delivery_charge_section_count = parseInt($("#delivery_charge_section_count_length").val());
            }   else {
                var delivery_charge_section_count = 1;
            } 


           
             // Add more rows
            $(".add-more-section").click(function(e) {
                e.preventDefault();
                addRowForDeliverChargeSection();
            });

            // Remove rows 
            $("#delivery_charge_section").on("click", "a.remove-section", function(e) {
                e.preventDefault();
                $(this).closest('.row').remove();
                delivery_charge_section_count--;
            });
            $("#delivery_charge_section").on("click", "a.remove-dynamic-delivery-charge-slab", function(e) {
                //e.preventDefault();
                var min_amount = $(this).attr("data-min-amount");
              
                $.post({
                    url: '{{route('admin.business-settings.remove-dynamic-delivery-charge-slab')}}',
                    data: {
                            "min_amount": min_amount,
                            "_token": "{{csrf_token()}}"
                           
                        },
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            var json_data = JSON.parse(data);
                            toastr.success(json_data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                });
            });
    
    

            if($("#time_slot_length").val() > 0){
                var count = parseInt($("#time_slot_length").val());
            }   else {
                var count = 1;
            } 
            
           

             // Add more rows
            $(".add-more-time-slot").click(function(e) {
                e.preventDefault();
                addRow();
            });

            // Remove rows and update labels
            $("#time_slot_data").on("click", "a.remove-time-slot", function(e) {
                e.preventDefault();
                $(this).closest('.row').remove();
                 manageLabels();
                count--;
            });

            $("#time_slot_data").on("click", "a.remove-dynamic-time-slot", function(e) {
                //e.preventDefault();
                var time_slot = $(this).attr("data-time-slot");
              
                $.post({
                    url: '{{route('admin.business-settings.remove-dynamic-time-slot')}}',
                    data: {
                            "time_slot": time_slot,
                            "_token": "{{csrf_token()}}"
                           
                        },
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            var json_data = JSON.parse(data);
                            toastr.success(json_data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                });
            });


            
       
            function manageLabels(){                
                // Update the labels for remaining rows
                let rows = $("#time_slot_data").children('.row');
                rows.each(function(index, row) {
                    let labels = $(row).find('label');
                    labels.eq(0).text(`Order From Time Slot ${index + 1}`);
                    labels.eq(1).text(`Order To Time Slot ${index + 1}`);
                });
            }
          
        function addRow(){
            count++;  
            var timeSlotData = '<div class="row">'+
                                    '<div class="col-md-6 col-12 slot-section'+count+'">'+
                                        '<div class="form-group">'+
                                            '<label class="input-label d-inline" for="exampleFormControlInput1">Order From Time Slot '+count+'</label>'+
                                            '<input type="time" name="order_from_time_slots[]" class="form-control">'+
                                        '</div>'+                    
                                    '</div>'+
                                    '<div class="col-md-6 col-12 slot-section'+count+'">'+
                                        '<div class="form-group">'+
                                            '<label class="input-label d-inline" for="exampleFormControlInput1">Order To Time Slot '+count+'</label>'+
                                            '<input type="time"  name="order_to_time_slots[]" class="form-control">'+
                                        '</div>'+   
                                    '<div  style="float:right;">'+
                                        '<a href="javascript:void(0)" class="remove-time-slot" data-count="'+count+'"> Remove </a>'+
                                    '</div>'+              
                                '</div>';
                       
                       
                        
            $("#time_slot_data").append(timeSlotData);

        }
        
        function addRowForDeliverChargeSection(){
            delivery_charge_section_count++;  
            var htmlData = '<div class="row">'+
                                    '<div class="col-md-4 col-12 deilvery-charge-section'+delivery_charge_section_count+'">'+
                                        '<div class="form-group">'+
                                            '<label class="input-label d-inline" for="exampleFormControlInput1">Min. Amount</label>'+
                                            '<input type="text" name="min_amount[]" class="form-control">'+
                                        '</div>'+                    
                                    '</div>'+
                                    '<div class="col-md-4 col-12 deilvery-charge-section'+delivery_charge_section_count+'">'+
                                        '<div class="form-group">'+
                                            '<label class="input-label d-inline" for="exampleFormControlInput1">Max. Amount</label>'+
                                            '<input type="text" name="max_amount[]" class="form-control">'+
                                        '</div>'+                    
                                    '</div>'+
                                    '<div class="col-md-4 col-12 deilvery-charge-section'+delivery_charge_section_count+'">'+
                                        '<div class="form-group">'+
                                            '<label class="input-label d-inline" for="exampleFormControlInput1">Delivery Charge</label>'+
                                            '<input type="text"  name="delivery_charge[]" class="form-control">'+
                                        '</div>'+   
                                    '<div  style="float:right;">'+
                                        '<a href="javascript:void(0)" class="remove-section" data-count="'+delivery_charge_section_count+'"> Remove </a>'+
                                    '</div>'+              
                                '</div>';
                       
                       
                        
            $("#delivery_charge_section").append(htmlData);

        }
  

        function maintenance_mode() {
        @if(env('APP_MODE')=='demo')
            toastr.warning('Sorry! You can not enable maintainance mode in demo!');
        @else
            Swal.fire({
                title: 'Are you sure?',
                text: 'Be careful before you turn on/off maintenance mode',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '{{route('admin.maintenance-mode')}}',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
        @endif
        };

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function previewFavIcon(input) {
            if (input.files && input.files[0]) {
                $("#viewer2").removeClass("d-none");
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
        
         $("#customFileEg2").change(function () {
            previewFavIcon(this);
        });
   

      

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
         </script>

@endpush
