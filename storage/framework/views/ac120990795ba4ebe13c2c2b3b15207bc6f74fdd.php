<?php $__env->startSection('title','Settings'); ?>
<?php
  $appEnv = env('APP_ENV');
  $assetPrefixPath = ($appEnv == 'local') ? '' : 'public';
  $language=\App\Models\BusinessSetting::where('key','language')->first();
  $language = $language->value ?? null;
  $map_api_key_data = \App\Models\BusinessSetting::where('key', 'map_api_key')->first();
  $map_api_key      = (isset($map_api_key_data) && !empty($map_api_key_data)) ? $map_api_key_data->value : '';
  $name=\App\Models\BusinessSetting::where('key','business_name')->first();
  $currency_symbol_position=\App\Models\BusinessSetting::where('key','currency_symbol_position')->first();
  $config=\App\CentralLogics\Helpers::get_business_settings('maintenance_mode');
?>
<?php $__env->startPush('css_or_js'); ?>
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
    @media  only screen and (max-width: 768px) {
        /* For mobile phones: */
        #location_map_canvas{
            height: 200px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><?php echo e(__('messages.business')); ?> <?php echo e(__('messages.setup')); ?></h1>
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
                                    <?php echo e(__('messages.maintenance_mode')); ?>

                                </h5>
                            </div>
                            <div class="col-6">
                                <label class="switch ml-3 float-right">
                                    <input type="checkbox" class="status" onclick="maintenance_mode()"
                                        <?php echo e(isset($config) && $config?'checked':''); ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="<?php echo e(route('admin.business-settings.update-setup')); ?>" method="post"
                      enctype="multipart/form-data">
                      <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">Business Name</label>
                        <input type="text" name="restaurant_name" value="<?php echo e($name->value??''); ?>" class="form-control"
                               placeholder="New Business" required>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <?php
                              $countryData =    \App\Models\BusinessSetting::where('key','country')->first();   
                              $country = (isset($countryData) && !empty($countryData)) ?? $countryData->value ;
                              
                            ?>
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="country"><?php echo e(__('messages.country')); ?></label>
                                <select id="country" name="country" class="form-control  js-select2-custom">
                                    <option value="AF" <?php echo e($country == "AF" ? 'selected' : ''); ?>>Afghanistan</option>
                                    <option value="AX" <?php echo e($country == "AX" ? 'selected' : ''); ?>>Åland Islands</option>
                                    <option value="AL" <?php echo e($country == "AL" ? 'selected' : ''); ?>>Albania</option>
                                    <option value="DZ" <?php echo e($country == "DZ" ? 'selected' : ''); ?>>Algeria</option>
                                    <option value="AS" <?php echo e($country == "AS" ? 'selected' : ''); ?>>American Samoa</option>
                                    <option value="AD" <?php echo e($country == "AD" ? 'selected' : ''); ?>>Andorra</option>
                                    <option value="AO" <?php echo e($country == "AO" ? 'selected' : ''); ?>>Angola</option>
                                    <option value="AI" <?php echo e($country == "AI" ? 'selected' : ''); ?>>Anguilla</option>
                                    <option value="AQ" <?php echo e($country == "AQ" ? 'selected' : ''); ?>>Antarctica</option>
                                    <option value="AG" <?php echo e($country == "AG" ? 'selected' : ''); ?>>Antigua and Barbuda</option>
                                    <option value="AR" <?php echo e($country == "AR" ? 'selected' : ''); ?>>Argentina</option>
                                    <option value="AM" <?php echo e($country == "AM" ? 'selected' : ''); ?>>Armenia</option>
                                    <option value="AW" <?php echo e($country == "AW" ? 'selected' : ''); ?>>Aruba</option>
                                    <option value="AU" <?php echo e($country == "AU" ? 'selected' : ''); ?>>Australia</option>
                                    <option value="AT" <?php echo e($country == "AT" ? 'selected' : ''); ?>>Austria</option>
                                    <option value="AZ" <?php echo e($country == "AZ" ? 'selected' : ''); ?>>Azerbaijan</option>
                                    <option value="BS" <?php echo e($country == "BS" ? 'selected' : ''); ?>>Bahamas</option>
                                    <option value="BH" <?php echo e($country == "BH" ? 'selected' : ''); ?>>Bahrain</option>
                                    <option value="BD" <?php echo e($country == "BD" ? 'selected' : ''); ?>>Bangladesh</option>
                                    <option value="BB" <?php echo e($country == "BB" ? 'selected' : ''); ?>>Barbados</option>
                                    <option value="BY" <?php echo e($country == "BY" ? 'selected' : ''); ?>>Belarus</option>
                                    <option value="BE" <?php echo e($country == "BE" ? 'selected' : ''); ?>>Belgium</option>
                                    <option value="BZ" <?php echo e($country == "BZ" ? 'selected' : ''); ?>>Belize</option>
                                    <option value="BJ" <?php echo e($country == "BJ" ? 'selected' : ''); ?>>Benin</option>
                                    <option value="BM" <?php echo e($country == "BM" ? 'selected' : ''); ?>>Bermuda</option>
                                    <option value="BT" <?php echo e($country == "BT" ? 'selected' : ''); ?>>Bhutan</option>
                                    <option value="BO" <?php echo e($country == "BO" ? 'selected' : ''); ?>>Bolivia, Plurinational State of</option>
                                    <option value="BQ" <?php echo e($country == "BQ" ? 'selected' : ''); ?>>Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA" <?php echo e($country == "BA" ? 'selected' : ''); ?>>Bosnia and Herzegovina</option>
                                    <option value="BW" <?php echo e($country == "BW" ? 'selected' : ''); ?>>Botswana</option>
                                    <option value="BV" <?php echo e($country == "BV" ? 'selected' : ''); ?>>Bouvet Island</option>
                                    <option value="BR" <?php echo e($country == "BR" ? 'selected' : ''); ?>>Brazil</option>
                                    <option value="IO" <?php echo e($country == "IO" ? 'selected' : ''); ?>>British Indian Ocean Territory</option>
                                    <option value="BN" <?php echo e($country == "BN" ? 'selected' : ''); ?>>Brunei Darussalam</option>
                                    <option value="BG" <?php echo e($country == "BG" ? 'selected' : ''); ?>>Bulgaria</option>
                                    <option value="BF" <?php echo e($country == "BF" ? 'selected' : ''); ?>>Burkina Faso</option>
                                    <option value="BI" <?php echo e($country == "BI" ? 'selected' : ''); ?>>Burundi</option>
                                    <option value="KH" <?php echo e($country == "KH" ? 'selected' : ''); ?>>Cambodia</option>
                                    <option value="CM" <?php echo e($country == "CM" ? 'selected' : ''); ?>>Cameroon</option>
                                    <option value="CA" <?php echo e($country == "CA" ? 'selected' : ''); ?>>Canada</option>
                                    <option value="CV" <?php echo e($country == "CV" ? 'selected' : ''); ?>>Cape Verde</option>
                                    <option value="KY" <?php echo e($country == "KY" ? 'selected' : ''); ?>>Cayman Islands</option>
                                    <option value="CF" <?php echo e($country == "CF" ? 'selected' : ''); ?>>Central African Republic</option>
                                    <option value="TD" <?php echo e($country == "TD" ? 'selected' : ''); ?>>Chad</option>
                                    <option value="CL" <?php echo e($country == "CL" ? 'selected' : ''); ?>>Chile</option>
                                    <option value="CN" <?php echo e($country == "CN" ? 'selected' : ''); ?>>China</option>
                                    <option value="CX" <?php echo e($country == "CX" ? 'selected' : ''); ?>>Christmas Island</option>
                                    <option value="CC" <?php echo e($country == "CC" ? 'selected' : ''); ?>>Cocos (Keeling) Islands</option>
                                    <option value="CO" <?php echo e($country == "CO" ? 'selected' : ''); ?>>Colombia</option>
                                    <option value="KM" <?php echo e($country == "KM" ? 'selected' : ''); ?>>Comoros</option>
                                    <option value="CG" <?php echo e($country == "CG" ? 'selected' : ''); ?>>Congo</option>
                                    <option value="CD" <?php echo e($country == "CD" ? 'selected' : ''); ?>>Congo, the Democratic Republic of the</option>
                                    <option value="CK" <?php echo e($country == "CK" ? 'selected' : ''); ?>>Cook Islands</option>
                                    <option value="CR" <?php echo e($country == "CR" ? 'selected' : ''); ?>>Costa Rica</option>
                                    <option value="CI" <?php echo e($country == "CI" ? 'selected' : ''); ?>>Côte d'Ivoire</option>
                                    <option value="HR" <?php echo e($country == "HR" ? 'selected' : ''); ?>>Croatia</option>
                                    <option value="CU" <?php echo e($country == "CU" ? 'selected' : ''); ?>>Cuba</option>
                                    <option value="CW" <?php echo e($country == "CW" ? 'selected' : ''); ?>>Curaçao</option>
                                    <option value="CY" <?php echo e($country == "CY" ? 'selected' : ''); ?>>Cyprus</option>
                                    <option value="CZ" <?php echo e($country == "CZ" ? 'selected' : ''); ?>>Czech Republic</option>
                                    <option value="DK" <?php echo e($country == "DK" ? 'selected' : ''); ?>>Denmark</option>
                                    <option value="DJ" <?php echo e($country == "DJ" ? 'selected' : ''); ?>>Djibouti</option>
                                    <option value="DM" <?php echo e($country == "DM" ? 'selected' : ''); ?>>Dominica</option>
                                    <option value="DO" <?php echo e($country == "DO" ? 'selected' : ''); ?>>Dominican Republic</option>
                                    <option value="EC" <?php echo e($country == "EC" ? 'selected' : ''); ?>>Ecuador</option>
                                    <option value="EG" <?php echo e($country == "EG" ? 'selected' : ''); ?>>Egypt</option>
                                    <option value="SV" <?php echo e($country == "SV" ? 'selected' : ''); ?>>El Salvador</option>
                                    <option value="GQ" <?php echo e($country == "GQ" ? 'selected' : ''); ?>>Equatorial Guinea</option>
                                    <option value="ER" <?php echo e($country == "ER" ? 'selected' : ''); ?>>Eritrea</option>
                                    <option value="EE" <?php echo e($country == "EE" ? 'selected' : ''); ?>>Estonia</option>
                                    <option value="ET" <?php echo e($country == "ET" ? 'selected' : ''); ?>>Ethiopia</option>
                                    <option value="FK" <?php echo e($country == "FK" ? 'selected' : ''); ?>>Falkland Islands (Malvinas)</option>
                                    <option value="FO" <?php echo e($country == "FO" ? 'selected' : ''); ?>>Faroe Islands</option>
                                    <option value="FJ" <?php echo e($country == "FJ" ? 'selected' : ''); ?>>Fiji</option>
                                    <option value="FI" <?php echo e($country == "FI" ? 'selected' : ''); ?>>Finland</option>
                                    <option value="FR" <?php echo e($country == "FR" ? 'selected' : ''); ?>>France</option>
                                    <option value="GF" <?php echo e($country == "GF" ? 'selected' : ''); ?>>French Guiana</option>
                                    <option value="PF" <?php echo e($country == "PF" ? 'selected' : ''); ?>>French Polynesia</option>
                                    <option value="TF" <?php echo e($country == "TF" ? 'selected' : ''); ?>>French Southern Territories</option>
                                    <option value="GA" <?php echo e($country == "GA" ? 'selected' : ''); ?>>Gabon</option>
                                    <option value="GM" <?php echo e($country == "GM" ? 'selected' : ''); ?>>Gambia</option>
                                    <option value="GE" <?php echo e($country == "GE" ? 'selected' : ''); ?>>Georgia</option>
                                    <option value="DE" <?php echo e($country == "DE" ? 'selected' : ''); ?>>Germany</option>
                                    <option value="GH" <?php echo e($country == "GH" ? 'selected' : ''); ?>>Ghana</option>
                                    <option value="GI" <?php echo e($country == "GI" ? 'selected' : ''); ?>>Gibraltar</option>
                                    <option value="GR" <?php echo e($country == "GR" ? 'selected' : ''); ?>>Greece</option>
                                    <option value="GL" <?php echo e($country == "GL" ? 'selected' : ''); ?>>Greenland</option>
                                    <option value="GD" <?php echo e($country == "GD" ? 'selected' : ''); ?>>Grenada</option>
                                    <option value="GP" <?php echo e($country == "GP" ? 'selected' : ''); ?>>Guadeloupe</option>
                                    <option value="GU" <?php echo e($country == "GU" ? 'selected' : ''); ?>>Guam</option>
                                    <option value="GT" <?php echo e($country == "GT" ? 'selected' : ''); ?>>Guatemala</option>
                                    <option value="GG" <?php echo e($country == "GG" ? 'selected' : ''); ?>>Guernsey</option>
                                    <option value="GN" <?php echo e($country == "GN" ? 'selected' : ''); ?>>Guinea</option>
                                    <option value="GW" <?php echo e($country == "GW" ? 'selected' : ''); ?>>Guinea-Bissau</option>
                                    <option value="GY" <?php echo e($country == "GY" ? 'selected' : ''); ?>>Guyana</option>
                                    <option value="HT" <?php echo e($country == "HT" ? 'selected' : ''); ?>>Haiti</option>
                                    <option value="HM" <?php echo e($country == "HM" ? 'selected' : ''); ?>>Heard Island and McDonald Islands</option>
                                    <option value="VA" <?php echo e($country == "VA" ? 'selected' : ''); ?>>Holy See (Vatican City State)</option>
                                    <option value="HN" <?php echo e($country == "HN" ? 'selected' : ''); ?>>Honduras</option>
                                    <option value="HK" <?php echo e($country == "HK" ? 'selected' : ''); ?>>Hong Kong</option>
                                    <option value="HU" <?php echo e($country == "HU" ? 'selected' : ''); ?>>Hungary</option>
                                    <option value="IS" <?php echo e($country == "IS" ? 'selected' : ''); ?>>Iceland</option>
                                    <option value="IN" <?php echo e($country == "IN" ? 'selected' : ''); ?>>India</option>
                                    <option value="ID" <?php echo e($country == "ID" ? 'selected' : ''); ?>>Indonesia</option>
                                    <option value="IR" <?php echo e($country == "IR" ? 'selected' : ''); ?>>Iran, Islamic Republic of</option>
                                    <option value="IQ" <?php echo e($country == "IQ" ? 'selected' : ''); ?>>Iraq</option>
                                    <option value="IE" <?php echo e($country == "IE" ? 'selected' : ''); ?>>Ireland</option>
                                    <option value="IM" <?php echo e($country == "IM" ? 'selected' : ''); ?>>Isle of Man</option>
                                    <option value="IL" <?php echo e($country == "IL" ? 'selected' : ''); ?>>Israel</option>
                                    <option value="IT" <?php echo e($country == "IT" ? 'selected' : ''); ?>>Italy</option>
                                    <option value="JM" <?php echo e($country == "JM" ? 'selected' : ''); ?>>Jamaica</option>
                                    <option value="JP" <?php echo e($country == "JP" ? 'selected' : ''); ?>>Japan</option>
                                    <option value="JE" <?php echo e($country == "JE" ? 'selected' : ''); ?>>Jersey</option>
                                    <option value="JO" <?php echo e($country == "JO" ? 'selected' : ''); ?>>Jordan</option>
                                    <option value="KZ" <?php echo e($country == "KZ" ? 'selected' : ''); ?>>Kazakhstan</option>
                                    <option value="KE" <?php echo e($country == "KE" ? 'selected' : ''); ?>>Kenya</option>
                                    <option value="KI" <?php echo e($country == "KI" ? 'selected' : ''); ?>>Kiribati</option>
                                    <option value="KP" <?php echo e($country == "KP" ? 'selected' : ''); ?>>Korea, Democratic People's Republic of</option>
                                    <option value="KR" <?php echo e($country == "KR" ? 'selected' : ''); ?>>Korea, Republic of</option>
                                    <option value="KW" <?php echo e($country == "KW" ? 'selected' : ''); ?>>Kuwait</option>
                                    <option value="KG" <?php echo e($country == "KG" ? 'selected' : ''); ?>>Kyrgyzstan</option>
                                    <option value="LA" <?php echo e($country == "LA" ? 'selected' : ''); ?>>Lao People's Democratic Republic</option>
                                    <option value="LV" <?php echo e($country == "LV" ? 'selected' : ''); ?>>Latvia</option>
                                    <option value="LB" <?php echo e($country == "LB" ? 'selected' : ''); ?>>Lebanon</option>
                                    <option value="LS" <?php echo e($country == "LS" ? 'selected' : ''); ?>>Lesotho</option>
                                    <option value="LR" <?php echo e($country == "LR" ? 'selected' : ''); ?>>Liberia</option>
                                    <option value="LY" <?php echo e($country == "LY" ? 'selected' : ''); ?>>Libya</option>
                                    <option value="LI" <?php echo e($country == "LI" ? 'selected' : ''); ?>>Liechtenstein</option>
                                    <option value="LT" <?php echo e($country == "LT" ? 'selected' : ''); ?>>Lithuania</option>
                                    <option value="LU" <?php echo e($country == "LU" ? 'selected' : ''); ?>>Luxembourg</option>
                                    <option value="MO" <?php echo e($country == "MO" ? 'selected' : ''); ?>>Macao</option>
                                    <option value="MK" <?php echo e($country == "MK" ? 'selected' : ''); ?>>Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG" <?php echo e($country == "MG" ? 'selected' : ''); ?>>Madagascar</option>
                                    <option value="MW" <?php echo e($country == "MW" ? 'selected' : ''); ?>>Malawi</option>
                                    <option value="MY" <?php echo e($country == "MY" ? 'selected' : ''); ?>>Malaysia</option>
                                    <option value="MV" <?php echo e($country == "MV" ? 'selected' : ''); ?>>Maldives</option>
                                    <option value="ML" <?php echo e($country == "ML" ? 'selected' : ''); ?>>Mali</option>
                                    <option value="MT" <?php echo e($country == "MT" ? 'selected' : ''); ?>>Malta</option>
                                    <option value="MH" <?php echo e($country == "MH" ? 'selected' : ''); ?>>Marshall Islands</option>
                                    <option value="MQ" <?php echo e($country == "MQ" ? 'selected' : ''); ?>>Martinique</option>
                                    <option value="MR" <?php echo e($country == "MR" ? 'selected' : ''); ?>>Mauritania</option>
                                    <option value="MU" <?php echo e($country == "MU" ? 'selected' : ''); ?>>Mauritius</option>
                                    <option value="YT" <?php echo e($country == "YT" ? 'selected' : ''); ?>>Mayotte</option>
                                    <option value="MX" <?php echo e($country == "MX" ? 'selected' : ''); ?>>Mexico</option>
                                    <option value="FM" <?php echo e($country == "FM" ? 'selected' : ''); ?>>Micronesia, Federated States of</option>
                                    <option value="MD" <?php echo e($country == "MD" ? 'selected' : ''); ?>>Moldova, Republic of</option>
                                    <option value="MC" <?php echo e($country == "MC" ? 'selected' : ''); ?>>Monaco</option>
                                    <option value="MN" <?php echo e($country == "MN" ? 'selected' : ''); ?>>Mongolia</option>
                                    <option value="ME" <?php echo e($country == "ME" ? 'selected' : ''); ?>>Montenegro</option>
                                    <option value="MS" <?php echo e($country == "MS" ? 'selected' : ''); ?>>Montserrat</option>
                                    <option value="MA" <?php echo e($country == "MA" ? 'selected' : ''); ?>>Morocco</option>
                                    <option value="MZ" <?php echo e($country == "MZ" ? 'selected' : ''); ?>>Mozambique</option>
                                    <option value="MM" <?php echo e($country == "MM" ? 'selected' : ''); ?>>Myanmar</option>
                                    <option value="NA" <?php echo e($country == "NA" ? 'selected' : ''); ?>>Namibia</option>
                                    <option value="NR" <?php echo e($country == "NR" ? 'selected' : ''); ?>>Nauru</option>
                                    <option value="NP" <?php echo e($country == "NP" ? 'selected' : ''); ?>>Nepal</option>
                                    <option value="NL" <?php echo e($country == "NL" ? 'selected' : ''); ?>>Netherlands</option>
                                    <option value="NC" <?php echo e($country == "NC" ? 'selected' : ''); ?>>New Caledonia</option>
                                    <option value="NZ" <?php echo e($country == "NZ" ? 'selected' : ''); ?>>New Zealand</option>
                                    <option value="NI" <?php echo e($country == "NI" ? 'selected' : ''); ?>>Nicaragua</option>
                                    <option value="NE" <?php echo e($country == "NE" ? 'selected' : ''); ?>>Niger</option>
                                    <option value="NG" <?php echo e($country == "NG" ? 'selected' : ''); ?>>Nigeria</option>
                                    <option value="NU" <?php echo e($country == "NU" ? 'selected' : ''); ?>>Niue</option>
                                    <option value="NF" <?php echo e($country == "NF" ? 'selected' : ''); ?>>Norfolk Island</option>
                                    <option value="MP" <?php echo e($country == "MP" ? 'selected' : ''); ?>>Northern Mariana Islands</option>
                                    <option value="NO" <?php echo e($country == "NO" ? 'selected' : ''); ?>>Norway</option>
                                    <option value="OM" <?php echo e($country == "OM" ? 'selected' : ''); ?>>Oman</option>
                                    <option value="PK" <?php echo e($country == "PK" ? 'selected' : ''); ?>>Pakistan</option>
                                    <option value="PW" <?php echo e($country == "PW" ? 'selected' : ''); ?>>Palau</option>
                                    <option value="PS" <?php echo e($country == "PS" ? 'selected' : ''); ?>>Palestinian Territory, Occupied</option>
                                    <option value="PA" <?php echo e($country == "PA" ? 'selected' : ''); ?>>Panama</option>
                                    <option value="PG" <?php echo e($country == "PG" ? 'selected' : ''); ?>>Papua New Guinea</option>
                                    <option value="PY" <?php echo e($country == "PY" ? 'selected' : ''); ?>>Paraguay</option>
                                    <option value="PE" <?php echo e($country == "PE" ? 'selected' : ''); ?>>Peru</option>
                                    <option value="PH" <?php echo e($country == "PH" ? 'selected' : ''); ?>>Philippines</option>
                                    <option value="PN" <?php echo e($country == "PN" ? 'selected' : ''); ?>>Pitcairn</option>
                                    <option value="PL" <?php echo e($country == "PL" ? 'selected' : ''); ?>>Poland</option>
                                    <option value="PT" <?php echo e($country == "PT" ? 'selected' : ''); ?>>Portugal</option>
                                    <option value="PR" <?php echo e($country == "PR" ? 'selected' : ''); ?>>Puerto Rico</option>
                                    <option value="QA" <?php echo e($country == "QA" ? 'selected' : ''); ?>>Qatar</option>
                                    <option value="RE" <?php echo e($country == "RE" ? 'selected' : ''); ?>>Réunion</option>
                                    <option value="RO" <?php echo e($country == "RO" ? 'selected' : ''); ?>>Romania</option>
                                    <option value="RU" <?php echo e($country == "RU" ? 'selected' : ''); ?>>Russian Federation</option>
                                    <option value="RW" <?php echo e($country == "RW" ? 'selected' : ''); ?>>Rwanda</option>
                                    <option value="BL" <?php echo e($country == "BL" ? 'selected' : ''); ?>>Saint Barthélemy</option>
                                    <option value="SH" <?php echo e($country == "SH" ? 'selected' : ''); ?>>Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN" <?php echo e($country == "KN" ? 'selected' : ''); ?>>Saint Kitts and Nevis</option>
                                    <option value="LC" <?php echo e($country == "LC" ? 'selected' : ''); ?>>Saint Lucia</option>
                                    <option value="MF" <?php echo e($country == "MF" ? 'selected' : ''); ?>>Saint Martin (French part)</option>
                                    <option value="PM" <?php echo e($country == "PM" ? 'selected' : ''); ?>>Saint Pierre and Miquelon</option>
                                    <option value="VC" <?php echo e($country == "VC" ? 'selected' : ''); ?>>Saint Vincent and the Grenadines</option>
                                    <option value="WS" <?php echo e($country == "WS" ? 'selected' : ''); ?>>Samoa</option>
                                    <option value="SM" <?php echo e($country == "SM" ? 'selected' : ''); ?>>San Marino</option>
                                    <option value="ST" <?php echo e($country == "ST" ? 'selected' : ''); ?>>Sao Tome and Principe</option>
                                    <option value="SA" <?php echo e($country == "SA" ? 'selected' : ''); ?>>Saudi Arabia</option>
                                    <option value="SN" <?php echo e($country == "SN" ? 'selected' : ''); ?>>Senegal</option>
                                    <option value="RS" <?php echo e($country == "RS" ? 'selected' : ''); ?>>Serbia</option>
                                    <option value="SC" <?php echo e($country == "SC" ? 'selected' : ''); ?>>Seychelles</option>
                                    <option value="SL" <?php echo e($country == "SL" ? 'selected' : ''); ?>>Sierra Leone</option>
                                    <option value="SG" <?php echo e($country == "SG" ? 'selected' : ''); ?>>Singapore</option>
                                    <option value="SX" <?php echo e($country == "SX" ? 'selected' : ''); ?>>Sint Maarten (Dutch part)</option>
                                    <option value="SK" <?php echo e($country == "SK" ? 'selected' : ''); ?>>Slovakia</option>
                                    <option value="SI" <?php echo e($country == "SI" ? 'selected' : ''); ?>>Slovenia</option>
                                    <option value="SB" <?php echo e($country == "SB" ? 'selected' : ''); ?>>Solomon Islands</option>
                                    <option value="SO" <?php echo e($country == "SO" ? 'selected' : ''); ?>>Somalia</option>
                                    <option value="ZA" <?php echo e($country == "ZA" ? 'selected' : ''); ?>>South Africa</option>
                                    <option value="GS" <?php echo e($country == "GS" ? 'selected' : ''); ?>>South Georgia and the South Sandwich Islands</option>
                                    <option value="SS" <?php echo e($country == "SS" ? 'selected' : ''); ?>>South Sudan</option>
                                    <option value="ES" <?php echo e($country == "ES" ? 'selected' : ''); ?>>Spain</option>
                                    <option value="LK" <?php echo e($country == "LK" ? 'selected' : ''); ?>>Sri Lanka</option>
                                    <option value="SD" <?php echo e($country == "SD" ? 'selected' : ''); ?>>Sudan</option>
                                    <option value="SR" <?php echo e($country == "SR" ? 'selected' : ''); ?>>Suriname</option>
                                    <option value="SJ" <?php echo e($country == "SJ" ? 'selected' : ''); ?>>Svalbard and Jan Mayen</option>
                                    <option value="SZ" <?php echo e($country == "SZ" ? 'selected' : ''); ?>>Swaziland</option>
                                    <option value="SE" <?php echo e($country == "SE" ? 'selected' : ''); ?>>Sweden</option>
                                    <option value="CH" <?php echo e($country == "CH" ? 'selected' : ''); ?>>Switzerland</option>
                                    <option value="SY" <?php echo e($country == "SY" ? 'selected' : ''); ?>>Syrian Arab Republic</option>
                                    <option value="TW" <?php echo e($country == "TW" ? 'selected' : ''); ?>>Taiwan, Province of China</option>
                                    <option value="TJ" <?php echo e($country == "TJ" ? 'selected' : ''); ?>>Tajikistan</option>
                                    <option value="TZ" <?php echo e($country == "TZ" ? 'selected' : ''); ?>>Tanzania, United Republic of</option>
                                    <option value="TH" <?php echo e($country == "TH" ? 'selected' : ''); ?>>Thailand</option>
                                    <option value="TL" <?php echo e($country == "TL" ? 'selected' : ''); ?>>Timor-Leste</option>
                                    <option value="TG" <?php echo e($country == "TG" ? 'selected' : ''); ?>>Togo</option>
                                    <option value="TK" <?php echo e($country == "TK" ? 'selected' : ''); ?>>Tokelau</option>
                                    <option value="TO" <?php echo e($country == "TO" ? 'selected' : ''); ?>>Tonga</option>
                                    <option value="TT" <?php echo e($country == "TT" ? 'selected' : ''); ?>>Trinidad and Tobago</option>
                                    <option value="TN" <?php echo e($country == "TN" ? 'selected' : ''); ?>>Tunisia</option>
                                    <option value="TR" <?php echo e($country == "TR" ? 'selected' : ''); ?>>Turkey</option>
                                    <option value="TM" <?php echo e($country == "TM" ? 'selected' : ''); ?>>Turkmenistan</option>
                                    <option value="TC" <?php echo e($country == "TC" ? 'selected' : ''); ?>>Turks and Caicos Islands</option>
                                    <option value="TV" <?php echo e($country == "TV" ? 'selected' : ''); ?>>Tuvalu</option>
                                    <option value="UG" <?php echo e($country == "UG" ? 'selected' : ''); ?>>Uganda</option>
                                    <option value="UA" <?php echo e($country == "UA" ? 'selected' : ''); ?>>Ukraine</option>
                                    <option value="AE" <?php echo e($country == "AE" ? 'selected' : ''); ?>>United Arab Emirates</option>
                                    <option value="GB" <?php echo e($country == "GB" ? 'selected' : ''); ?>>United Kingdom</option>
                                    <option value="US" <?php echo e($country == "US" ? 'selected' : ''); ?>>United States</option>
                                    <option value="UM" <?php echo e($country == "UM" ? 'selected' : ''); ?>>United States Minor Outlying Islands</option>
                                    <option value="UY" <?php echo e($country == "UY" ? 'selected' : ''); ?>>Uruguay</option>
                                    <option value="UZ" <?php echo e($country == "UZ" ? 'selected' : ''); ?>>Uzbekistan</option>
                                    <option value="VU" <?php echo e($country == "VU" ? 'selected' : ''); ?>>Vanuatu</option>
                                    <option value="VE" <?php echo e($country == "VE" ? 'selected' : ''); ?>>Venezuela, Bolivarian Republic of</option>
                                    <option value="VN" <?php echo e($country == "VN" ? 'selected' : ''); ?>>Viet Nam</option>
                                    <option value="VG" <?php echo e($country == "VG" ? 'selected' : ''); ?>>Virgin Islands, British</option>
                                    <option value="VI" <?php echo e($country == "VI" ? 'selected' : ''); ?>>Virgin Islands, U.S.</option>
                                    <option value="WF" <?php echo e($country == "WF" ? 'selected' : ''); ?>>Wallis and Futuna</option>
                                    <option value="EH" <?php echo e($country == "EH" ? 'selected' : ''); ?>>Western Sahara</option>
                                    <option value="YE" <?php echo e($country == "YE" ? 'selected' : ''); ?>>Yemen</option>
                                    <option value="ZM" <?php echo e($country == "ZM" ? 'selected' : ''); ?>>Zambia</option>
                                    <option value="ZW" <?php echo e($country == "ZW" ? 'selected' : ''); ?>>Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                 
                        <div class="col-md-4 col-sm-6 col-12">
                            <?php ($tz=\App\Models\BusinessSetting::where('key','timezone')->first()); ?>
                            <?php ($tz=$tz?$tz->value:0); ?>
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize"><?php echo e(__('messages.time_zone')); ?></label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    <option value="UTC" <?php echo e($tz?($tz==''?'selected':''):''); ?>>UTC</option>
                                    <option value="Etc/GMT+12"  <?php echo e($tz?($tz=='Etc/GMT+12'?'selected':''):''); ?>>(GMT-12:00) International Date Line West</option>
                                    <option value="Pacific/Midway"  <?php echo e($tz?($tz=='Pacific/Midway'?'selected':''):''); ?>>(GMT-11:00) Midway Island, Samoa</option>
                                    <option value="Pacific/Honolulu"  <?php echo e($tz?($tz=='Pacific/Honolulu'?'selected':''):''); ?>>(GMT-10:00) Hawaii</option>
                                    <option value="US/Alaska"  <?php echo e($tz?($tz=='US/Alaska'?'selected':''):''); ?>>(GMT-09:00) Alaska</option>
                                    <option value="America/Los_Angeles"  <?php echo e($tz?($tz=='America/Los_Angeles'?'selected':''):''); ?>>(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value="America/Tijuana"  <?php echo e($tz?($tz=='America/Tijuana'?'selected':''):''); ?>>(GMT-08:00) Tijuana, Baja California</option>
                                    <option value="US/Arizona"  <?php echo e($tz?($tz=='US/Arizona'?'selected':''):''); ?>>(GMT-07:00) Arizona</option>
                                    <option value="America/Chihuahua"  <?php echo e($tz?($tz=='America/Chihuahua'?'selected':''):''); ?>>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="US/Mountain"  <?php echo e($tz?($tz=='US/Mountain'?'selected':''):''); ?>>(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option value="America/Managua"  <?php echo e($tz?($tz=='America/Managua'?'selected':''):''); ?>>(GMT-06:00) Central America</option>
                                    <option value="US/Central"  <?php echo e($tz?($tz=='US/Central'?'selected':''):''); ?>>(GMT-06:00) Central Time (US & Canada)</option>
                                    <option value="America/Mexico_City"  <?php echo e($tz?($tz=='America/Mexico_City'?'selected':''):''); ?>>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value="Canada/Saskatchewan"  <?php echo e($tz?($tz=='Canada/Saskatchewan'?'selected':''):''); ?>>(GMT-06:00) Saskatchewan</option>
                                    <option value="America/Bogota"  <?php echo e($tz?($tz=='America/Bogota'?'selected':''):''); ?>>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option value="US/Eastern"  <?php echo e($tz?($tz=='US/Eastern'?'selected':''):''); ?>>(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option value="US/East-Indiana"  <?php echo e($tz?($tz=='US/East-Indiana'?'selected':''):''); ?>>(GMT-05:00) Indiana (East)</option>
                                    <option value="Canada/Atlantic"  <?php echo e($tz?($tz=='Canada/Atlantic'?'selected':''):''); ?>>(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option value="America/Caracas"  <?php echo e($tz?($tz=='America/Caracas'?'selected':''):''); ?>>(GMT-04:00) Caracas, La Paz</option>
                                    <option value="America/Manaus"  <?php echo e($tz?($tz=='America/Manaus'?'selected':''):''); ?>>(GMT-04:00) Manaus</option>
                                    <option value="America/Santiago"  <?php echo e($tz?($tz=='America/Santiago'?'selected':''):''); ?>>(GMT-04:00) Santiago</option>
                                    <option value="Canada/Newfoundland"  <?php echo e($tz?($tz=='Canada/Newfoundland'?'selected':''):''); ?>>(GMT-03:30) Newfoundland</option>
                                    <option value="America/Sao_Paulo"  <?php echo e($tz?($tz=='America/Sao_Paulo'?'selected':''):''); ?>>(GMT-03:00) Brasilia</option>
                                    <option value="America/Argentina/Buenos_Aires"  <?php echo e($tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''); ?>>(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option value="America/Godthab"  <?php echo e($tz?($tz=='America/Godthab'?'selected':''):''); ?>>(GMT-03:00) Greenland</option>
                                    <option value="America/Montevideo"  <?php echo e($tz?($tz=='America/Montevideo'?'selected':''):''); ?>>(GMT-03:00) Montevideo</option>
                                    <option value="America/Noronha"  <?php echo e($tz?($tz=='America/Noronha'?'selected':''):''); ?>>(GMT-02:00) Mid-Atlantic</option>
                                    <option value="Atlantic/Cape_Verde"  <?php echo e($tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''); ?>>(GMT-01:00) Cape Verde Is.</option>
                                    <option value="Atlantic/Azores"  <?php echo e($tz?($tz=='Atlantic/Azores'?'selected':''):''); ?>>(GMT-01:00) Azores</option>
                                    <option value="Africa/Casablanca"  <?php echo e($tz?($tz=='Africa/Casablanca'?'selected':''):''); ?>>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option value="Etc/Greenwich"  <?php echo e($tz?($tz=='Etc/Greenwich'?'selected':''):''); ?>>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option value="Europe/Amsterdam"  <?php echo e($tz?($tz=='Europe/Amsterdam'?'selected':''):''); ?>>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value="Europe/Belgrade"  <?php echo e($tz?($tz=='Europe/Belgrade'?'selected':''):''); ?>>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value="Europe/Brussels"  <?php echo e($tz?($tz=='Europe/Brussels'?'selected':''):''); ?>>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="Europe/Sarajevo"  <?php echo e($tz?($tz=='Europe/Sarajevo'?'selected':''):''); ?>>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value="Africa/Lagos"  <?php echo e($tz?($tz=='Africa/Lagos'?'selected':''):''); ?>>(GMT+01:00) West Central Africa</option>
                                    <option value="Asia/Amman"  <?php echo e($tz?($tz=='Asia/Amman'?'selected':''):''); ?>>(GMT+02:00) Amman</option>
                                    <option value="Europe/Athens"  <?php echo e($tz?($tz=='Europe/Athens'?'selected':''):''); ?>>(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option value="Asia/Beirut"  <?php echo e($tz?($tz=='Asia/Beirut'?'selected':''):''); ?>>(GMT+02:00) Beirut</option>
                                    <option value="Africa/Cairo"  <?php echo e($tz?($tz=='Africa/Cairo'?'selected':''):''); ?>>(GMT+02:00) Cairo</option>
                                    <option value="Africa/Harare"  <?php echo e($tz?($tz=='Africa/Harare'?'selected':''):''); ?>>(GMT+02:00) Harare, Pretoria</option>
                                    <option value="Europe/Helsinki"  <?php echo e($tz?($tz=='Europe/Helsinki'?'selected':''):''); ?>>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value="Asia/Jerusalem"  <?php echo e($tz?($tz=='Asia/Jerusalem'?'selected':''):''); ?>>(GMT+02:00) Jerusalem</option>
                                    <option value="Europe/Minsk"  <?php echo e($tz?($tz=='Europe/Minsk'?'selected':''):''); ?>>(GMT+02:00) Minsk</option>
                                    <option value="Africa/Windhoek"  <?php echo e($tz?($tz=='Africa/Windhoek'?'selected':''):''); ?>>(GMT+02:00) Windhoek</option>
                                    <option value="Asia/Kuwait"  <?php echo e($tz?($tz=='Asia/Kuwait'?'selected':''):''); ?>>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option value="Europe/Moscow"  <?php echo e($tz?($tz=='Europe/Moscow'?'selected':''):''); ?>>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option value="Africa/Nairobi"  <?php echo e($tz?($tz=='Africa/Nairobi'?'selected':''):''); ?>>(GMT+03:00) Nairobi</option>
                                    <option value="Asia/Tbilisi"  <?php echo e($tz?($tz=='Asia/Tbilisi'?'selected':''):''); ?>>(GMT+03:00) Tbilisi</option>
                                    <option value="Asia/Tehran"  <?php echo e($tz?($tz=='Asia/Tehran'?'selected':''):''); ?>>(GMT+03:30) Tehran</option>
                                    <option value="Asia/Muscat"  <?php echo e($tz?($tz=='Asia/Muscat'?'selected':''):''); ?>>(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option value="Asia/Baku"  <?php echo e($tz?($tz=='Asia/Baku'?'selected':''):''); ?>>(GMT+04:00) Baku</option>
                                    <option value="Asia/Yerevan"  <?php echo e($tz?($tz=='Asia/Yerevan'?'selected':''):''); ?>>(GMT+04:00) Yerevan</option>
                                    <option value="Asia/Kabul"  <?php echo e($tz?($tz=='Asia/Kabul'?'selected':''):''); ?>>(GMT+04:30) Kabul</option>
                                    <option value="Asia/Yekaterinburg"  <?php echo e($tz?($tz=='Asia/Yekaterinburg'?'selected':''):''); ?>>(GMT+05:00) Yekaterinburg</option>
                                    <option value="Asia/Karachi"  <?php echo e($tz?($tz=='Asia/Karachi'?'selected':''):''); ?>>(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option value="Asia/Calcutta"  <?php echo e($tz?($tz=='Asia/Calcutta'?'selected':''):''); ?>>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <!-- <option value="Asia/Calcutta"  <?php echo e($tz?($tz=='Asia/Calcutta'?'selected':''):''); ?>>(GMT+05:30) Sri Jayawardenapura</option> -->
                                    <option value="Asia/Katmandu"  <?php echo e($tz?($tz=='Asia/Katmandu'?'selected':''):''); ?>>(GMT+05:45) Kathmandu</option>
                                    <option value="Asia/Almaty"  <?php echo e($tz?($tz=='Asia/Almaty'?'selected':''):''); ?>>(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option value="Asia/Dhaka"  <?php echo e($tz?($tz=='Asia/Dhaka'?'selected':''):''); ?>>(GMT+06:00) Astana, Dhaka</option>
                                    <option value="Asia/Rangoon"  <?php echo e($tz?($tz=='Asia/Rangoon'?'selected':''):''); ?>>(GMT+06:30) Yangon (Rangoon)</option>
                                    <option value="Asia/Bangkok"  <?php echo e($tz?($tz=='"Asia/Bangkok'?'selected':''):''); ?>>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="Asia/Krasnoyarsk"  <?php echo e($tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''); ?>>(GMT+07:00) Krasnoyarsk</option>
                                    <option value="Asia/Hong_Kong"  <?php echo e($tz?($tz=='Asia/Hong_Kong'?'selected':''):''); ?>>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value="Asia/Kuala_Lumpur"  <?php echo e($tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''); ?>>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option value="Asia/Irkutsk"  <?php echo e($tz?($tz=='Asia/Irkutsk'?'selected':''):''); ?>>(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option value="Australia/Perth"  <?php echo e($tz?($tz=='Australia/Perth'?'selected':''):''); ?>>(GMT+08:00) Perth</option>
                                    <option value="Asia/Taipei"  <?php echo e($tz?($tz=='Asia/Taipei'?'selected':''):''); ?>>(GMT+08:00) Taipei</option>
                                    <option value="Asia/Tokyo"  <?php echo e($tz?($tz=='Asia/Tokyo'?'selected':''):''); ?>>(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value="Asia/Seoul"  <?php echo e($tz?($tz=='Asia/Seoul'?'selected':''):''); ?>>(GMT+09:00) Seoul</option>
                                    <option value="Asia/Yakutsk"  <?php echo e($tz?($tz=='Asia/Yakutsk'?'selected':''):''); ?>>(GMT+09:00) Yakutsk</option>
                                    <option value="Australia/Adelaide"  <?php echo e($tz?($tz=='Australia/Adelaide'?'selected':''):''); ?>>(GMT+09:30) Adelaide</option>
                                    <option value="Australia/Darwin"  <?php echo e($tz?($tz=='Australia/Darwin'?'selected':''):''); ?>>(GMT+09:30) Darwin</option>
                                    <option value="Australia/Brisbane"  <?php echo e($tz?($tz=='Australia/Brisbane'?'selected':''):''); ?>>(GMT+10:00) Brisbane</option>
                                    <option value="Australia/Canberra"  <?php echo e($tz?($tz=='Australia/Canberra'?'selected':''):''); ?>>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value="Australia/Hobart"  <?php echo e($tz?($tz=='Australia/Hobart'?'selected':''):''); ?>>(GMT+10:00) Hobart</option>
                                    <option value="Pacific/Guam"  <?php echo e($tz?($tz=='Pacific/Guam'?'selected':''):''); ?>>(GMT+10:00) Guam, Port Moresby</option>
                                    <option value="Asia/Vladivostok"  <?php echo e($tz?($tz=='Asia/Vladivostok'?'selected':''):''); ?>>(GMT+10:00) Vladivostok</option>
                                    <option value="Asia/Magadan"  <?php echo e($tz?($tz=='Asia/Magadan'?'selected':''):''); ?>>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option value="Pacific/Auckland"  <?php echo e($tz?($tz=='Pacific/Auckland'?'selected':''):''); ?>>(GMT+12:00) Auckland, Wellington</option>
                                    <option value="Pacific/Fiji"  <?php echo e($tz?($tz=='Pacific/Fiji'?'selected':''):''); ?>>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option value="Pacific/Tongatapu"  <?php echo e($tz?($tz=='Pacific/Tongatapu'?'selected':''):''); ?>>(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <?php ($tf=\App\Models\BusinessSetting::where('key','timeformat')->first()); ?>
                            <?php ($tf=$tf?$tf->value:'24'); ?>
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize"><?php echo e(__('messages.time_format')); ?></label>
                                <select name="time_format" class="form-control">
                                    <option value="12" <?php echo e($tf=='12'?'selected':''); ?>><?php echo e(__('messages.12_hour')); ?></option>
                                    <option value="24" <?php echo e($tf=='24'?'selected':''); ?>><?php echo e(__('messages.24_hour')); ?></option>
                                </select>
                            </div>

                        </div>
                    </div>
                   

                  

                    <div class="row">
                        <?php ($phone=\App\Models\BusinessSetting::where('key','phone')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1"><?php echo e(__('messages.phone')); ?></label>
                                <input type="text" value="<?php echo e($phone->value??''); ?>"
                                       name="phone" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        <?php ($email=\App\Models\BusinessSetting::where('key','email_address')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1"><?php echo e(__('messages.email')); ?></label>
                                <input type="email" value="<?php echo e($email->value??''); ?>"
                                       name="email" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php ($mininum_order_amount=\App\Models\BusinessSetting::where('key','mininum_order_amount')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Mininum Order Amount</label>
                                <input type="text" value="<?php echo e($mininum_order_amount->value??''); ?>"
                                       name="mininum_order_amount" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        <?php ($order_installment_percent_1=\App\Models\BusinessSetting::where('key','order_installment_percent_1')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 1</label>
                                <input type="text" value="<?php echo e($order_installment_percent_1->value??''); ?>"
                                       name="order_installment_percent_1" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <?php ($order_installment_percent_2=\App\Models\BusinessSetting::where('key','order_installment_percent_2')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 2</label>
                                <input type="text" value="<?php echo e($order_installment_percent_2->value??''); ?>"
                                       name="order_installment_percent_2" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                        <?php ($order_installment_percent_3=\App\Models\BusinessSetting::where('key','order_installment_percent_3')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Order Installment Percent 3</label>
                                <input type="text" value="<?php echo e($order_installment_percent_3->value??''); ?>"
                                       name="order_installment_percent_3" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php ($delivery_charge=\App\Models\BusinessSetting::where('key','delivery_charge')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Delivery Charge</label>
                                <input type="text" value="<?php echo e($delivery_charge->value??''); ?>"
                                       name="delivery_charge" class="form-control" placeholder="">
                            </div>
                        </div>
                        <?php ($referred_discount=\App\Models\BusinessSetting::where('key','referred_discount')->first()); ?>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">Referred Discount (%)</label>
                                <input type="text" value="<?php echo e($referred_discount->value??''); ?>"
                                       name="referred_discount" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <?php

                        $order_time_slot_data = \App\Models\BusinessSetting::where('key','order_time_slots')->first();
                        $order_time_slot_data = explode(",",$order_time_slot_data->value);

                    ?>
                    <input type="hidden" id="time_slot_length"  value="<?php echo e(count($order_time_slot_data)); ?>">

                    <div  id="time_slot_data">
                        
                            <?php
                            
                          
                            if(isset($order_time_slot_data) && !empty($order_time_slot_data)){
                            foreach($order_time_slot_data as $key => $value){
                                $time_slot_data = explode("-",$value);
                                $from_time_slot = $time_slot_data[0];
                                $to_time_slot   = $time_slot_data[1];
                    
                            ?>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label class="input-label d-inline" for="exampleFormControlInput1">Order From Time Slot <?php echo e($key+1); ?> </label>
            <input type="time" value="<?php echo e($from_time_slot ?? ''); ?>" name="order_from_time_slots[]" class="form-control">
        </div>                         
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label class="input-label d-inline" for="exampleFormControlInput1">Order To Time Slot <?php echo e($key+1); ?></label>
            <input type="time" value="<?php echo e($to_time_slot ?? ''); ?>" name="order_to_time_slots[]" class="form-control">
        </div>                           
    </div>

</div>
                         
                            
                            <?php }} else { ?>
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


                            <?php } ?>
                            
                        
                    </div>
               
                    <div  style='float:right;'>
                         <a href="javascript:void(0)" class="add-more-time-slot"> Add More </a>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <?php ($address=\App\Models\BusinessSetting::where('key','address')->first()); ?>
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1"><?php echo e(__('messages.address')); ?></label>
                                <textarea type="text" id="address"
                                       name="address" class="form-control" placeholder="" rows="1"
                                       required><?php echo e($address->value??''); ?></textarea>
                            </div>
                            <?php ($default_location=\App\Models\BusinessSetting::where('key','default_location')->first()); ?>
                            <?php
                                $default_location = '';
                                if(isset($default_location) && !empty($default_location)){
                                    $default_location = $default_location->value?json_decode($default_location->value, true):0;
                                }
                            ?>
                             

                           
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="latitude"><?php echo e(__('messages.latitude')); ?><span
                                        class="input-label-secondary" title="<?php echo e(__('messages.click_on_the_map_select_your_defaul_location')); ?>"><img src="<?php echo e(asset($assetPrefixPath.'/assets/admin/img/info-circle.svg')); ?>" alt="<?php echo e(__('messages.click_on_the_map_select_your_defaul_location')); ?>"></span></label>
                                <input type="text" id="latitude"
                                       name="latitude" class="form-control d-inline"
                                       placeholder="Ex : -94.22213" value="<?php echo e($default_location?$default_location['lat']:0); ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="longitude"><?php echo e(__('messages.longitude')); ?><span
                                        class="input-label-secondary" title="<?php echo e(__('messages.click_on_the_map_select_your_defaul_location')); ?>"><img src="<?php echo e(asset($assetPrefixPath.'/assets/admin/img/info-circle.svg')); ?>" alt="<?php echo e(__('messages.click_on_the_map_select_your_defaul_location')); ?>"></span></label>
                                <input type="text"
                                       name="longitude" class="form-control"
                                       placeholder="Ex : 103.344322" id="longitude" value="<?php echo e($default_location?$default_location['lng']:0); ?>" required readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input id="pac-input" class="controls rounded" style="height: 3em;width:fit-content;" title="<?php echo e(__('messages.search_your_location_here')); ?>" type="text" placeholder="<?php echo e(__('messages.search_here')); ?>"/>
                            <div id="location_map_canvas"></div>
                        </div>
                    </div>

                    <div class="row">
                    <?php ($footer_text=\App\Models\BusinessSetting::where('key','footer_text')->first()); ?>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1"><?php echo e(__('messages.footer')); ?> <?php echo e(__('messages.text')); ?></label>
                                <textarea type="text" value=""
                                       name="footer_text" class="form-control" placeholder=""
                                       required><?php echo e($footer_text->value??''); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <?php ($logo=\App\Models\BusinessSetting::where('key','logo')->first()); ?>
                    <?php ($logo=$logo->value??''); ?>
                    <div class="form-group">
                        <label class="input-label d-inline"><?php echo e(__('messages.logo')); ?></label><small style="color: red">* ( <?php echo e(__('messages.ratio')); ?> 3:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1"><?php echo e(__('messages.choose')); ?> <?php echo e(__('messages.file')); ?></label>
                        </div>
                        <hr>
                        <?php
                
                        $logoPath = (env('APP_ENV') == 'local') ? asset('storage/business/' . $logo) : asset('storage/app/public/business/' . $logo);        
                    ?>
                        <center>
                            <img style="height: 100px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 onerror="this.src='<?php echo e(asset($assetPrefixPath . '/admin/img/160x160/img2.jpg')); ?>'"
                                 src="<?php echo e($logoPath); ?>" alt="logo image"/>
                        </center>
                    </div>
                    <hr>
                    <button type="<?php echo e(env('APP_MODE')!='demo'?'submit':'button'); ?>" onclick="<?php echo e(env('APP_MODE')!='demo'?'':'call_demo()'); ?>" class="btn btn-primary mb-2"><?php echo e(trans('messages.submit')); ?></button>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>

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

        
               
        

      

 
      
      //  let language = <?php echo($language); ?>;
     //   $('[id=language]').val(language);

        function maintenance_mode() {
        <?php if(env('APP_MODE')=='demo'): ?>
            toastr.warning('Sorry! You can not enable maintainance mode in demo!');
        <?php else: ?>
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
                        url: '<?php echo e(route('admin.maintenance-mode')); ?>',
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
        <?php endif; ?>
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

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($map_api_key); ?>&libraries=places&v=3.45.8"></script>
    <script>
        function initAutocomplete() {
            var myLatLng = { lat: <?php echo e($default_location?$default_location['lat']:'-33.8688'); ?>, lng: <?php echo e($default_location?$default_location['lng']:'151.2195'); ?> };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: <?php echo e($default_location?$default_location['lat']:'-33.8688'); ?>, lng: <?php echo e($default_location?$default_location['lng']:'151.2195'); ?> },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();
            <?php ($country=\App\Models\BusinessSetting::where('key','country')->first()); ?>

            <?php if($country): ?>
            $("#country option[value='<?php echo e($country->value); ?>']").attr('selected', 'selected').change();
            <?php endif; ?>



            $("#free_delivery_over_status").on('change', function(){
                if($("#free_delivery_over_status").is(':checked')){
                    $('#free_delivery_over').removeAttr('readonly');
                } else {
                    $('#free_delivery_over').attr('readonly', true);
                    $('#free_delivery_over').val('0');
                }
            });
        });

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/brobo/resources/views/admin-views/business-settings/business-index.blade.php ENDPATH**/ ?>