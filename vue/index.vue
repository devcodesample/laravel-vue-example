<template>
<v-layout column justify-center align-center>
    <div class="full-template">
        <v-container>
            <v-row>
                <v-col cols="12" md="6" sm="6">
                    <div class="account">
                        <h1>Hello <span>{{user.profile.first_name}} {{user.profile.last_name}}</span></h1>
                        <p>Make sure to keep your profile updated and fill out as much as you can on your profile to help clients
                            find you!</p>
                    </div>
                </v-col>
                <v-col cols="12" md="6" sm="6">
                    <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(updateProfilePic)" enctype="multipart/form-data">
                            <v-row class="account-right-top">
                                <v-col cols="12" md="6" sm="6">
                                    <h3>Profile Picture</h3>
                                    <ValidationProvider rules="required|image" v-slot="{ errors, validate }">
                                        <v-file-input @change="validate" ref="inputFile" v-model="files" label="Select Image File..."></v-file-input>
                                        <span class="vee_validator">{{ errors[0] }}</span>
                                    </ValidationProvider>
                                    <!-- </p> -->
                                    <p class="small">
                                        For best quality please upload a 400px x 400px image<br>
                                        Max Filesize: 4MB<br>
                                        Please only use letters and numbers in the filename.
                                    </p>
                                    <p class="account-submit">
                                        <input type="submit" class="small-green-button" name="submit" id="photo_submit1" value="Upload Photo">
                                    </p>
                                </v-col>
                                <v-col cols="12" md="6" sm="6">
                                    <div class="account-portrait-holder">
                                        <img v-bind:src="profilePic" style="margin-top:-2px; margin-left:-5px;">
                                    </div>
                                </v-col>
                            </v-row>
                        </form>
                    </ValidationObserver>
                </v-col>
            </v-row>
        </v-container>
    </div>
    <section class="field-background full">
        <div class="container">
            <div class="row account-sidebar">
                <div class="col-sm-3 col-md-3 col-12">
                    <div id="sticker-sticky-wrapper" class="sticky-wrapper" style="height: 950px;">
                        <div id="sticker" class="hide-for-small">
                            <h3>Edit Profile</h3>
                            <button class="small-red-button profile_submit_side" id="profile_submit_side">Save Changes</button>
                            <button @click="logout" class="small-red-button ">Logout</button>
                            <h3>Profile Completion</h3>
                            <div class="progress round ">
                                <span class="complete-text" style="display: none; position: absolute;">100%</span>
                                <span class="meter" style="width: 20%;"></span>
                            </div>
                            <h3>Jump To</h3>
                            <ul class="sidebar-nav">
                                <li><a href="/account#have-questions" class="">Have Questions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="account-fields col-sm-9 col-md-9 col-12">
                    <div id="undefined-sticky-wrapper" class="undefined-sticky-wrapper">
                        <div class="floating-save text-center custom-floating" style="display: none; width: 924px; right: 60px; z-index: 1; background: rgb(209, 149, 131); padding: 7px 0px; position: fixed; top: 0px;">
                            <a href="/account#" class="small-red-button  profile_submit_floating">Save Profile</a>
                        </div>
                    </div>
                    <h3 id="details" class="panel-header">Profile Details</h3>
                    <span>
                        <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                            <form id="mainform" name="mainform" @submit.prevent="handleSubmit(save)">
                                <div class="panel">
                                    <h4 class="first">Basic Contact Information</h4>
                                    <div class="row account-section">
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first_name" class="on show">Provider Type <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="alpha_spaces|required" v-slot="{ errors }">
                                                        <select @change="setProviderType($event)" v-model="user.profile.provider_type" name="provider_type" id="provider_type" class="txt form-control">
                                                            <option v-for="(prov,index) in provider_type.data" :key="index">{{prov.provider_name}}</option>
                                                        </select>
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="credential_initials">Credential Initials</label>
                                                <span>

                                                    <input type="text" v-model="user.profile.credential_initials" maxlength="100" name="credential_initials" id="credential_initials" placeholder="Credentials" class="txt form-control">

                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="first_name" class="on show">First Name <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="alpha_spaces|required" v-slot="{ errors }">
                                                        <input type="text" v-model="user.profile.first_name" name="first_name" id="first_name" maxlength="14" placeholder="First Name" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="on show">Contact Email Address <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="email|required" v-slot="{ errors }">
                                                        <input name="email" v-model="user.email" id="email" placeholder="Contact Email Address" type="email" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="license_number">License Number (optional)</label>
                                                <input name="license_number" v-model="user.profile.license_number" id="license_number" value="" placeholder="License Number (optional)" class="txt form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="last_name" class="on show">Last Name <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="alpha_spaces|required" v-slot="{ errors }">
                                                        <input type="text" name="last_name" v-model="user.profile.last_name" id="last_name" maxlength="18" value="userdatavalue.profile.last_name" placeholder="Last Name" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="website_url">Website URL (http://...)</label>
                                                <span>
                                                    <ValidationProvider :rules="{regex: /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/}" v-slot="{ errors }">
                                                        <input name="website_url" id="website_url" v-model="user.profile.website_url" placeholder="Website URL (http://)" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="card_blurb">Card Blurb (110 Character Max) <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="max:110|required" v-slot="{ errors }">
                                                        <input name="card_blurb" maxlength="110" v-model="user.profile.card_blurb" id="card_blurb" value="" placeholder="Card Blurb (110 Character Max)" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <span data-width="258" title="" class="tip-centered-bottom">This blurb will be displayed when a client hovers over your profile in the directory</span>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Office Address</h4>
                                    <div class="row account-section">
                                        <div class="col-sm-12 col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="profile_phone">Phone Number ### ### #### <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider :rules="{required:true,regex: /^\d{3} \d{3} \d{4}$/}" v-slot="{ errors }">
                                                        <input v-model="user.profile.office_contact" type="text" name="office_contact" id="office_contact" placeholder="Phone Number" class="txt form-control">
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="address_1">Locations <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                                        <v-select label="Choose Location" v-model="selected_locations" :multiple="true" item-text="location_name" item-value="id" :items="locations" filled chips deletable-chips small-chips prepend-icon="map">
                                                        </v-select>
                                                        <span class="vee_validator">{{ errors[0] }}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <h4>Secondary Office Address</h4>
                                    <div class="row account-section">
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="profile_sec_address_1">Address Line 1 (50 Characters max)</label>
                                                <span>
                                                    <ValidationProvider rules="max:50" v-slot="{ errors }">
                                                        <input v-model="user.profile.secondary_address_line_one" maxlength="50" name="secondary_address_line_one" id="secondary_address_line_one" placeholder="Address Line 1" class="txt form-control">
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="profile_sec_address_2">Address Line 2 (50 Characters max)</label>
                                                <span>
                                                    <ValidationProvider rules="max:50" v-slot="{ errors }">
                                                        <input v-model="user.profile.secondary_address_line_two" maxlength="50" name="secondary_address_line_two" id="secondary_address_line_two" placeholder="Address Line 2" class="txt form-control">
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <label for="secondary_contact">Phone Number ### ### ####</label>
                                                <span>
                                                    <ValidationProvider :rules="{regex: /^\d{3} \d{3} \d{4}$/}" v-slot="{ errors }">
                                                        <input v-model="user.profile.secondary_contact" name="secondary_contact" id="secondary_contact" placeholder="Phone Number" class="txt form-control">
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="profile_sec_city">City</label>
                                                <span>
                                                    <ValidationProvider rules="alpha_spaces" v-slot="{ errors }">
                                                        <input v-model="user.profile.secondary_city" name="secondary_city" id="secondary_city" placeholder="City" class="txt form-control">
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <div class="row">
                                                <div class="pd-0 col-sm-7 col-md-7 col-12">
                                                    <div class="form-group">
                                                        <label for="secondary_state">State</label>
                                                        <select v-model="user.profile.secondary_state" name="secondary_state" id="secondary_state" class="txt form-control">
                                                            <option value="">State</option>
                                                            <option>Alabama</option>
                                                            <option>Alaska</option>
                                                            <option>Arizona</option>
                                                            <option>Arkansas</option>
                                                            <option>California</option>
                                                            <option>Colorado</option>
                                                            <option>Connecticut</option>
                                                            <option>Delaware</option>
                                                            <option>Florida</option>
                                                            <option>Georgia</option>
                                                            <option>Hawaii</option>
                                                            <option>Idaho</option>
                                                            <option>Illinois</option>
                                                            <option>Indiana</option>
                                                            <option>Iowa</option>
                                                            <option>Kansas</option>
                                                            <option>Kentucky[E]</option>
                                                            <option>Louisiana</option>
                                                            <option>Maine</option>
                                                            <option>Maryland</option>
                                                            <option>Massachusetts[E]</option>
                                                            <option>Michigan</option>
                                                            <option>Minnesota</option>
                                                            <option>Mississippi</option>
                                                            <option>Missouri</option>
                                                            <option>Montana</option>
                                                            <option>Nebraska</option>
                                                            <option>Nevada</option>
                                                            <option>New Hampshire</option>
                                                            <option>New Jersey</option>
                                                            <option>New Mexico</option>
                                                            <option>New York</option>
                                                            <option>North Carolina</option>
                                                            <option>North Dakota</option>
                                                            <option>Ohio</option>
                                                            <option>Oklahoma</option>
                                                            <option>Oregon</option>
                                                            <option>Pennsylvania[E]</option>
                                                            <option>Rhode Island[F]</option>
                                                            <option>South Carolina</option>
                                                            <option>South Dakota</option>
                                                            <option>Tennessee</option>
                                                            <option>Texas</option>
                                                            <option>Utah</option>
                                                            <option>Vermont</option>
                                                            <option>Virginia[E]</option>
                                                            <option>Washington</option>
                                                            <option>West Virginia</option>
                                                            <option>Wisconsin</option>
                                                            <option>Wyoming</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="pd-0 col-sm-5 col-md-5 col-12">
                                                    <div class="form-group">
                                                        <label for="secondary_address_zip">Zip Code</label>
                                                        <input v-model="user.profile.secondary_address_zip" name="secondary_address_zip" id="secondary_address_zip" placeholder="Zip Code" class="txt form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row account-section">
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <h4>Practicing Since <span style="color: rgb(191, 0, 0);">*</span></h4>
                                            <span>
                                                <ValidationProvider rules="required" v-slot="{ errors }">
                                                    <select name="practicing_since" id="practicing_since" v-model="user.profile.practicing_since" class="form-control">
                                                        <option value="">Year</option>
                                                        <option v-for="(value, index) in yearsL" :value="value" :key="index">{{value}}</option>
                                                    </select>
                                                    <span class="vee_validator">{{errors[0]}}</span>
                                                </ValidationProvider>
                                            </span>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <h4>Practice name</h4>
                                            <span>
                                                <ValidationProvider rules="alpha_spaces" v-slot="{ errors }">
                                                    <input v-model="user.profile.office_neighborhood" name="practice_name" id="practice_name" placeholder="Practice name" class="txt form-control">
                                                    <span class="vee_validator">{{errors[0]}}</span>
                                                </ValidationProvider>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="account-section">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6 col-12">
                                                <h4>Cost Range ($ Per Session)</h4>
                                                <div class="row">
                                                    <div class="col-sm-5 col-md-5 col-12">
                                                        <div class="form-group">
                                                            <label for="profile_cost_min">Low Range <span class="vee_validator">*</span></label>
                                                            <span>
                                                                <ValidationProvider rules="required" v-slot="{ errors }">
                                                                    <input v-model="user.profile.cost_range_low" name="profile_cost_min" id="profile_cost_min" placeholder="Low Range" class="txt form-control">
                                                                    <span class="vee_validator">{{errors[0]}}</span>
                                                                </ValidationProvider>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-md-2 col-12">
                                                        <h4 class="first" style="padding-top: 10px;">To</h4>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5 col-12">
                                                        <div class="form-group">
                                                            <label for="profile_cost_max">High Range <span class="vee_validator">*</span>
                                                            </label>
                                                            <span>
                                                                <ValidationProvider rules="required" v-slot="{ errors }">
                                                                    <input v-model="user.profile.cost_range_high" name="profile_cost_max" id="profile_cost_max" placeholder="High Range" class="txt form-control">
                                                                    <span class="vee_validator">{{errors[0]}}</span>
                                                                </ValidationProvider>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6 col-12">
                                                <h4>Do you offer a free consultation? <span class="vee_validator">*</span></h4>
                                                <label for="profile_one_time">
                                                    <span>
                                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                                            <input type="checkbox" :value="user.profile.free_consultation" id="profile_one_time" name="profile_one_time" v-model="user.profile.free_consultation">
                                                            <span class="vee_validator">{{errors[0]}}</span>
                                                        </ValidationProvider>
                                                    </span>
                                                    <span id="one_time" class="custom checkbox"></span> Yes, I offer a free consultation.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <v-row>
                                        <v-col cols="12" md="12" sm="12">
                                            <h4>Payment Method</h4>
                                            <!-- <input type="hidden" value="" id="checked_insurance" v-model="checked_insurance"> -->
                                            <v-row>
                                                <v-col cols="12" md="12" sm="12">
                                                    <ul class="insurance-companies">
                                                        <li>
                                                            <v-layout row wrap>
                                                                <v-flex v-for="(insurance,index) in insuranceitem" :key="index" xs6>
                                                                    <v-checkbox light :label="insurance.insurance_name" :value="insurance.insurance_name" v-model="insurance_accepted">
                                                                    </v-checkbox>
                                                                </v-flex>
                                                            </v-layout>
                                                        </li>
                                                    </ul>
                                                </v-col>
                                            </v-row>
                                        </v-col>
                                    </v-row>
                                    <h4>Services</h4>
                                    <p>Select the types of counseling you provide (choose as many as apply).</p>
                                    <div class="row account-section">
                                        <v-row class=" account-section">
                                            <v-col cols="12" md="12" sm="12">
                                                <v-select v-model="selected_services" :multiple="true" :items="servicesitem" filled chips deletable-chips label="Select" small-chips>
                                                </v-select>
                                            </v-col>
                                        </v-row>
                                    </div>
                                    <br><br>
                                    <h4>Profile Text</h4>
                                    <div class="row account-section">
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <p>Introduce yourself to prospective clients. This text will appear on your full profile page and will show up, along with your photo, in search results.</p>
                                            <div class="form-group">
                                                <label for="profile_description">What was your path to becoming a {{user.profile.provider_type}} ? (300 Character Max) <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                                        <textarea v-model="user.profile.profile_intro" maxlength="300" name="profile_description" id="profile_description" placeholder="Intro" class="txt form-control area"></textarea>
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                            <p>In this space, elaborate on your credentials, experience and specialties.</p>
                                            <div class="form-group"><label for="profile_background">My Background (1000 Character Max)
                                                    <span class="vee_validator">*</span></label>
                                                <span>
                                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                                        <textarea v-model="user.profile.bio" maxlength="1000" name="profile_background" id="profile_background" placeholder="My Background" class="txt form-control area"></textarea>
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-12">
                                            <p>In this space, share details about your therapy orientation and details on how you operate your practice. Give clients a sense of how you prefer to work.</p>
                                            <div class="form-group"><label for="profile_approach">My Approach (1000 Character Max)
                                                    <span class="vee_validator">*</span></label> <span>
                                                    <ValidationProvider rules="required" v-slot="{ errors }">
                                                        <textarea maxlength="1000" v-model="user.profile.profile_approach" name="profile_approach" id="profile_approach" placeholder="My Approach" class="txt form-control area"></textarea>
                                                        <span class="vee_validator">{{errors[0]}}</span>
                                                    </ValidationProvider>
                                                </span></div>
                                        </div>
                                    </div> <br><br>
                                    <!-- <Issues :providerType="provider_type_val_for_childs" /> -->
                                    <h4>All Issues</h4>
                                    <p>Select any additional issues that you are comfortable with treating. These issues will display as text links to topics you have experience with, but may not be your main specialties like the issues above. This can help with visibility from clients, as well as search results for your profile page.</p>
                                    <v-row class=" account-section">
                                        <v-col cols="12" md="12" sm="12">
                                            <v-select v-model="all_issues" :items="issuesitem" filled chips deletable-chips label="Select" multiple small-chips>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <!-- <div>
                                        <Techniques :providerType="provider_type_val_for_childs" />
                                    </div> -->
                                    <h4>All Techniques</h4>
                                    <p>Select any additional techniques that you are experienced with. These techniques will display as text links to additional topics you want on your page, in addition to what you selected above. This can help with visibility from clients, as well as search results for your profile page.</p>
                                    <v-row class=" account-section">
                                        <v-col cols="12" md="12" sm="12">
                                            <v-select v-model="all_techniques" :items="techniquesitem" filled chips deletable-chips label="Select" multiple small-chips>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <div class="form-group">
                                        <label for="website_url">My Message to you Video URL (https://player.vimeo.com/video/400378398)</label>
                                        <span>
                                            <ValidationProvider :rules="{regex: /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/}" v-slot="{ errors }">
                                                <input name="video_message_url" v-model="user.profile.profile_video_message_url" placeholder="My Message to you Video URL (https://player.vimeo.com/video/400378398)" class="txt form-control">
                                                <span class="vee_validator">{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </span>
                                    </div>
                                    <div class="custom-btn-submit">
                                        <button name="main-from-btn" id="main-from-btn" @click="handleClick($event)" type="submit" class="medium-red-button" style="cursor: pointer;">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </ValidationObserver>
                    </span>
                    <section id="video_message_section" class="image_box">
                        <h3 class="panel-header">Video Message</h3>
                        <div class="panel">
                            <div class="account-section">
                                <p class="license_descrip">Your Video appered on my Message to section of your profile .</p>
                                <template v-if="user.profile.profile_video_message_url">
                                    <iframe width="100%" height="350" :src="user.profile.profile_video_message_url" frameborder="0" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                </template>
                                <template v-else>
                                    <h5>Post a video to introduce yourself here.</h5>
                                </template>

                                <!-- <ValidationObserver ref="form" v-slot="{ handleSubmit }">
<form @submit.prevent="handleSubmit(updateVideoMessage)" enctype="multipart/form-data">
<v-row class="account-right-top">
<v-col cols="12" md="6" sm="6">
<h3>Video Message</h3>
<ValidationProvider rules="required" v-slot="{ errors, validate }">
  <v-file-input @change="validate" ref="inputFile" v-model="video_message" label="Select Video File..."></v-file-input>
  <span class="vee_validator">{{ errors[0] }}</span>
</ValidationProvider>
<p class="account-submit">
  <input type="submit" class="small-green-button" name="submit" id="photo_video" value="Upload your Video message">
</p>
</v-col>
<v-col cols="12" md="6" sm="6">
<div class="">
 <div class="video-player-box" v-video-player:myVideoPlayer="playerOptions"></div>
</div>
</v-col>
</v-row>
</form>

</ValidationObserver> -->
                            </div>
                        </div>
                    </section>
                    <section class="subscribe">
                        <h3 id="have-questions" class="panel-header">HAVE QUESTIONS?</h3>
                        <div class="panel">
                            <div class="col-sm-12 col-md-12 col-12">
                                <p class="sub-label">If you have any questions on how to set up your profile, just let us know at
                                    <a href="/mailto:support@behuman.ly">support@behuman.ly</a>!
                                </p>
                            </div>
                        </div>
                    </section>
                    <section id="license_form" class="image_box">
                        <h3 class="panel-header">License or Pre-Licensed Image</h3>
                        <div class="panel">
                            <div class="account-section">
                                <p class="license_descrip">Photograph or scan your license or pre-license and upload it here for
                                    review. It will not be viewable by the public. Your live profile may be de-listed without this
                                    file.</p>
                                <ValidationObserver ref="form" v-slot="{ handleSubmit }">
                                    <form @submit.prevent="handleSubmit(updateLicencePic)" enctype="multipart/form-data">
                                        <v-row class="account-right-top">
                                            <v-col cols="12" md="6" sm="6">
                                                <h3>Licence Picture</h3>
                                                <ValidationProvider rules="required|image" v-slot="{ errors, validate }">
                                                    <v-file-input @change="validate" ref="inputFile" v-model="files_two" label="Select Image File..." accept="image/*"></v-file-input>
                                                    <span class="vee_validator">{{ errors[0] }}</span>
                                                </ValidationProvider>
                                                <!-- </p> -->
                                                <p class="small">
                                                    Image Dimensions: 400px x 400px<br>
                                                    Max Filesize: 4MB<br>
                                                    Please only use letters and numbers in&nbsp;the&nbsp;filename.
                                                </p>

                                                <p class="account-submit">
                                                    <input type="submit" class="small-green-button" name="submit" id="photo_submit" value="Upload Licence Image">
                                                </p>
                                            </v-col>
                                            <v-col cols="12" md="6" sm="6">
                                                <div class="account-portrait-holder new-acc">
                                                    <img v-bind:src="licence_image" style="margin-top:-2px; margin-left:-5px;">
                                                </div>
                                            </v-col>
                                        </v-row>
                                    </form>

                                </ValidationObserver>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</v-layout>
</template>

<script>
import 'sweetalert2/dist/sweetalert2.min.css';
import {
    apiConst
} from '@/assets/traits/apiConst'
import {
    ValidationProvider,
    ValidationObserver
} from 'vee-validate';
import {
    mapGetters,
    mapMutations,
    mapState
} from 'vuex'
import _ from 'lodash'

export default {
    middleware: 'auth',
    layout() {
        return 'primary'
    },
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    async asyncData(context) {
        let {
            data
        } = await context.app.$axios.get("/providers", {
            headers: apiConst._header
        });
        return {
            provider_type: data
        };
    },
    data() {
        return {
            user: null,
            yearsL: [],
            insuranceitem: [],
            locations: [],
            servicesitem: [],
            provider_type: [],
            insurance_accepted: [],
            selected_services: [],
            selected_locations: [],
            issuesitem: [],
            all_issues: [],
            techniquesitem: [],
            all_techniques: [],
            mainSubmitBtn: '',
            files: null,
            files_two: null,
            profilePic: '',
            licence_image: '',
            path: null,
            root: null,
            provider_type_val_for_childs: '',
            providerTypePrevCheck: ''
        }
    },
    created() {

        this.user = _.cloneDeep(this.$store.state.auth)
        this.path = _.cloneDeep(this.$store.state.default_paths)
        this.root = _.cloneDeep(this.$store.state.assets_roots)
        this.provider_type_val_for_childs = this.user.profile.provider_type

        this.yearsList()
        if (this.user.profile.insurance_accepted) {
            let insurance_accepted_string = this.user.profile.insurance_accepted
            let insurance_accepted_array = insurance_accepted_string.split(",")
            this.insurance_accepted = insurance_accepted_array
        }
        if (this.user.profile.services) {
            let services_string = this.user.profile.services
            let services_array = services_string.split(",")
            this.selected_services = services_array
        }
        if (this.user.profile.all_issues) {
            let issues_string = this.user.profile.all_issues
            let issues_array = issues_string.split(",")
            this.all_issues = issues_array
        }
        if (this.user.profile.all_techniques) {
            var techniques_string = this.user.profile.all_techniques;
            var techniques_array = techniques_string.split(",");
            this.all_techniques = techniques_array;
        }
        if (this.user.profile.profile_pic) {
            this.profilePic = this.path.assets_path + this.root.profile_img + this.user.profile.profile_pic
        } else {
            this.profilePic = this.path.assets_path + this.root.default_images + 'default_avatar.png'
        }
        if (this.user.profile.licence_image) {
            this.licence_image = this.path.assets_path + this.root.licence_img + this.user.profile.licence_image
        } else {
            this.licence_image = this.path.assets_path + this.root.default_images + 'default_licence.png'
        }

    },
    watch: {
        provider_type_val_for_childs: function (newProvider_type_val_for_childs, oldProvider_type_val_for_childs) {
            this.getTechniques().then(result => {
                this.techniquesitem = result.data.data.map(element => element.technique_name)
                this.techNameByIDval = result.data.data.map(element => ({
                    'id': element.technique_code,
                    'name': element.technique_name
                }))
                return this.techniquesitem
            }).catch(err => {

                console.log("Technique api error :726")
            })
            this.getIssues().then(result => {
                this.issuesitem = result.data.data.map(element => element.issues_name)
                return this.issuesitem
            }).catch(err => {
                console.log("Issue api error :713")
            })
        }
    },
    methods: {
        setProviderType(event) {

            if (this.providerTypePrevCheck) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "Once you change the provider type your Techniques and Issuess data of current provider type will be reseted and you won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Change it!'
                }).then((r) => {
                    this.$axios.post('user/purge/tech/issues', {
                        therapist_id: this.user.id,
                        provider_type: this.user.profile.provider_type
                    }, {
                        'headers': apiConst._header
                    }).then(result => {
                        if (r.value) {
                            this.$swal(
                                '',
                                'Techniques and Issuess data are reseted successfully',
                                'success'
                            )
                        }
                        this.all_techniques = []
                        this.all_issues = []
                        this.$store.commit('SET_PROVIDER_TYPE', this.user.profile.provider_type)
                        this.$store.commit('SET_TECH_ISSUE_AFTER_PROVIDER_CHANGE', '')
                        this.user = _.cloneDeep(this.$store.state.auth)
                    }).catch(err => {
                        console.log(err)
                    })
                })
            }
            this.provider_type_val_for_childs = event.target.value

        },
        updateProfilePic() {
            let formData = new FormData()
            const params = {
                'user_id': this.user.id,
                'pic': this.files
            }

            Object.entries(params).forEach(
                ([key, value]) => formData.append(key, value)
            )

            this.$axios.post('user/update/profile/pic', formData, {
                    'headers': apiConst._header
                }).then(result => {

                    this.profilePic = this.path.assets_path + this.root.profile_img + result.data.data
                    this.$store.commit('SET_PROFILE_PIC', result.data.data)
                    this.user = _.cloneDeep(this.$store.state.auth)

                    this.$toast.success(this.$options.filters.clearApiMessages(result.data.description), {
                        icon: 'check'
                    })
                    this.files = null
                })
                .catch(error => {

                    this.$toast.error(this.$options.filters.clearApiMessages(error.response.data.validation_error.pic), {
                        icon: 'error'
                    })
                })
        },
        updateLicencePic() {
            let formData = new FormData()
            const params = {
                'user_id': this.user.id,
                'licence_pic': this.files_two
            }
            Object.entries(params).forEach(
                ([key, value]) => formData.append(key, value)
            )

            this.$axios.post('user/update/licence/pic', formData, {
                    'headers': apiConst._header
                })
                .then(result => {
                    this.licence_image = this.path.assets_path + this.root.licence_img + result.data.data
                    this.$store.commit('SET_LICENCE_PIC', result.data.data)
                    this.user = _.cloneDeep(this.$store.state.auth)
                    this.$toast.success(this.$options.filters.clearApiMessages(result.data.description), {
                        icon: 'check'
                    })
                    this.files_two = null
                })
                .catch(error => {
                    this.$toast.error(this.$options.filters.clearApiMessages(error.response.data.validation_error.licence_pic), {
                        icon: 'error'
                    })
                })
        },

        handleClick(e) {
            this.mainSubmitBtn = e.target.name;
            return this.mainSubmitBtn
        },
        logout() {
            this.$store.commit('LOGOUT_AUTH_USER', null);
            this.$store.commit('AUTH', false)
            this.$router.push('/')
            this.$toast.success('Logged Out !')
        },
        yearsList() {
            let nowY = new Date().getFullYear()
            for (let Y = nowY; Y >= 1950; Y--) {
                this.yearsL.push(Y)

            }
            return this.yearsL;
        },
        async getInsurances() {
            const insurance = await this.$axios.get('insurance', {
                'headers': apiConst._header
            })
            return insurance
        },
        async getLocations() {
            const location = await this.$axios.get('location/list', {
                'headers': apiConst._header
            })
            return location
        },

        async getServices() {
            const services = await this.$axios.get('services', {
                'headers': apiConst._header
            })
            return services
        },
        async getIssues() {
            const issues = await this.$axios.get('issues/by/providerType', {
                'headers': apiConst._header,
                params: {
                    provider_type: this.provider_type_val_for_childs
                }
            })
            return issues
        },
        async getTherapistLocations() {
            const therapist_loc = await this.$axios.get('user/therapist/locations', {
                'headers': apiConst._header,
                params: {
                    therapist_id: this.user.id
                }
            })
            return therapist_loc
        },
        async getTechniques() {
            const techniques = await this.$axios.get('techniques/by/providerType', {
                'headers': apiConst._header,
                params: {
                    provider_type: this.provider_type_val_for_childs
                }
            })
            return techniques
        },
        save() {
            let formData = [{
                therapiest_id: this.user.id,
                first_name: this.user.profile.first_name,
                last_name: this.user.profile.last_name,
                email: this.user.email,
                credential_initials: this.user.profile.credential_initials,
                license_number: this.user.profile.license_number,
                website_url: this.user.profile.website_url,
                card_blurb: this.user.profile.card_blurb,
                office_contact: this.user.profile.office_contact,
                secondary_address_line_one: this.user.profile.secondary_address_line_one,
                secondary_address_line_two: this.user.profile.secondary_address_line_two,
                secondary_city: this.user.profile.secondary_city,
                secondary_state: this.user.profile.secondary_state,
                secondary_address_zip: this.user.profile.secondary_address_zip,
                secondary_contact: this.user.profile.secondary_contact,
                practicing_since: this.user.profile.practicing_since,
                practice_name: this.user.profile.office_neighborhood,
                cost_range_low: this.user.profile.cost_range_low,
                cost_range_high: this.user.profile.cost_range_high,
                free_consultation: this.user.profile.free_consultation,
                insurance_accepted: this.insurance_accepted.toString(),
                services: this.selected_services.toString(),
                profile_intro: this.user.profile.profile_intro,
                bio: this.user.profile.profile_intro,
                profile_approach: this.user.profile.profile_approach,
                all_issues: this.all_issues.toString(),
                all_techniques: this.all_techniques.toString(),
                profile_video_message_url: this.user.profile.profile_video_message_url,
                providerType: this.user.profile.provider_type,
                location_ids: this.selected_locations
            }];
            this.$axios.post('user/create/profile', formData, {
                    'headers': apiConst._header
                })
                .then(result => {
                    if (this.mainSubmitBtn == 'main-from-btn') {
                        this.$swal(
                            '',
                            'Your profile has been saved successfully.',
                            'success'
                        )
                        this.mainSubmitBtn = ''
                    }
                    this.$store.commit('SET_AUTH_USER', result.data.data)
                    this.user = _.cloneDeep(this.$store.state.auth)
                })
                .catch(error => {
                    let errors = Object.values(error.response.data.validation_error)
                    this.$toast.error(this.$options.filters.clearApiMessages(errors), {
                        icon: 'error'
                    })
                })
        }
    },
    filters: {
        clearApiMessages: function (array) {
            let messages = ''
            if (typeof array === 'string') {
                return messages = array
            }
            for (let index = 0; index < array.length; index++) {
                messages += array[index] + '<br/><br/>'
            }
            return messages
        }
    },
    mounted() {
        if (window.outerWidth <= 320) {
            $("#top-svg-acc").removeAttr("viewBox");
            $("#top-svg-acc").each(function () {
                $(this)[0].setAttribute("viewBox", "150 173 200 150");
            });
        }
        this.providerTypePrevCheck = this.user.profile.provider_type
        $('.profile_submit_side').on('click', function (e) {
            e.preventDefault();
            $('#main-from-btn').click()
        })
        this.getLocations().then(result => {

            const values = result.data.data
            this.locations = values
            return this.locations
        }).catch(err => {
            console.log("Insurance api error :701")
        })
        this.getTherapistLocations().then(result => {
            const values = Object.values(result.data.data)
            this.selected_locations = values
            return this.selected_services
        }).catch(err => {
            console.log("Therapist Location api error :701")
        })

        this.getInsurances().then(result => {
            const values = Object.values(result.data.data)
            this.insuranceitem = values
            return this.insuranceitem
        }).catch(err => {
            console.log("Insurance api error :701")
        })
        this.getServices().then(result => {
            this.servicesitem = result.data.data.map(element => element.service_name)
            return this.servicesitem
        }).catch(err => {
            console.log("service api error :687")
        })
        this.getIssues().then(result => {
            this.issuesitem = result.data.data.map(element => element.issues_name)
            return this.issuesitem
        }).catch(err => {
            console.log("Issue api error :713")
        })
        this.getTechniques().then(result => {
            this.techniquesitem = result.data.data.map(element => element.technique_name)
            this.techNameByIDval = result.data.data.map(element => ({
                'id': element.technique_code,
                'name': element.technique_name
            }))
            return this.techniquesitem
        }).catch(err => {

            console.log("Technique api error :726")
        })

        window.onscroll = function () {
            myFunction()
        };
        window.onscroll = function () {
            cumyFunction()
        };

        function myFunction() {
            if (document.body.scrollTop > 450 || document.documentElement.scrollTop > 450) {
                document.getElementById("sticker-sticky-wrapper").className = "is-sticky";

            } else {
                document.getElementById("sticker-sticky-wrapper").className = "";
            }
            if (document.body.scrollTop > 450 || document.documentElement.scrollTop > 450) {
                document.getElementById("undefined-sticky-wrapper").className = "is-sticky-1";
            } else {
                document.getElementById("undefined-sticky-wrapper").className = "";
            }
        }

        function cumyFunction() {
            if (document.body.scrollTop > 450 || document.documentElement.scrollTop > 450) {
                document.getElementById("undefined-sticky-wrapper").className = "is-sticky-1";
            } else {
                document.getElementById("undefined-sticky-wrapper").className = "";
            }
        }
    }

}
</script>
