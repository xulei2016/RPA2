<template>
    <layout title="完善信息" :left="left" >
        <div style="margin-top: 60px;padding: 6px;">
            <van-divider>基本信息</van-divider>
            <van-divider>请检查信息是否正确</van-divider>
            <van-cell-group>
                <van-field  placeholder="请输入姓名" :readonly="disabled" label="姓名" v-model="form.name" :error-message="errors['form.name']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        v-model="form.sex"
                        required
                        readonly
                        clickable
                        label="性别"
                        placeholder="选择性别"
                        @click="genderPicker = !disabled"
                        :error-message="errors['form.sex']"
                />
            </van-cell-group>
            <van-cell-group v-if="!disabled">
                <van-field
                        v-model="form.birthday"
                        required
                        readonly
                        clickable
                        label="出生日期"
                        placeholder="出生日期"
                        @click="birthdayPicker = !disabled"
                        :error-message="errors['form.birthday']"
                />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="证件号码" :readonly="disabled" label="证件号码" v-model="form.zjbh" :error-message="errors['form.zjbh']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="证件地址" :readonly="disabled"  label="证件地址" v-model="form.sfz_address" :error-message="errors['form.sfz_address']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        v-model="form.sfz_date_end"
                        required
                        readonly
                        clickable
                        label="证件有效期"
                        placeholder="证件有效期"
                        @click="cardPicker = !disabled"
                        :error-message="errors['form.sfz_date_end']"
                />
            </van-cell-group>
            <van-divider>归属营业部</van-divider>
            <van-cell-group>
                <van-field
                        v-model="dept"
                        required
                        readonly
                        clickable
                        label="所属部门"
                        placeholder="所属部门"
                        @click="deptPicker = !disabled"
                        :error-message="errors['form.dept_id']"
                />
            </van-cell-group>
            <van-divider>详细信息</van-divider>
            <van-cell-group>
                <van-field  placeholder="联系地址" :readonly="disabled" label="联系地址" v-model="form.address" :error-message="errors['form.address']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="邮政编码" :readonly="disabled"  label="邮政编码" v-model="form.postal_code" :error-message="errors['form.postal_code']"  />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="邮箱" :readonly="disabled" label="邮箱" v-model="form.email" :error-message="errors['form.email']"   />
            </van-cell-group>



            <van-divider>教育信息</van-divider>

            <van-cell-group>
                <van-field
                        v-model="form.edu_background"
                        required
                        readonly
                        clickable
                        label="学历/学位"
                        placeholder="学历/学位"
                        @click="educationPicker = !disabled"
                        :error-message="errors['form.edu_background']"
                />
            </van-cell-group>

            <!--            <div>-->
            <!--                <h2 class="doc">学历证书</h2>-->
            <!--                <van-uploader :max-count="1" :deletable="!disabled" v-model="edu_img" name="edu_img" upload-text="学历证书" :disabled="disabled"  :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >-->
            <!--                </van-uploader>-->
            <!--            </div>-->
            <!--            <div >-->
            <!--                <h2 class="doc">学位证书(非必填)</h2>-->
            <!--                <van-uploader :max-count="1" :deletable="!disabled" v-model="edu_degree_img" name="edu_degree_img" upload-text="学位证书" :disabled="disabled"  :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >-->
            <!--                </van-uploader>-->
            <!--            </div>-->



            <van-divider>其它信息</van-divider>
            <van-cell-group>
                <van-field
                        v-model="form.profession"
                        required
                        readonly
                        clickable
                        label="职业"
                        placeholder="职业"
                        @click="professionPicker = !disabled"
                        :error-message="errors['form.profession']"
                />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="客户经理号" :readonly="disabled"  label="客户经理号" v-model="form.manager_number" :error-message="errors['form.manager_number']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-switch-cell :disabled="disabled"  title="是否通过期货从业资格考试" v-model="showCheckbox" :error-message="errors['form.is_exam']" required />
            </van-cell-group>

            <van-cell-group v-if="showCheckbox">
                <van-field  placeholder="从业资格合格证编号" :readonly="disabled"  label="从业资格合格证编号" v-model="form.exam_number"  required />
            </van-cell-group>

            <div v-if="showCheckbox">
                <h2 class="doc">上传从业资格合格证照片</h2>
                <van-uploader name="exam_img" upload-text="上传从业资格合格证照片" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" >
                    <img :src="exam_img" width="96%" alt="">
                </van-uploader>
            </div>


            <div style="text-align: center">
                <van-button style="margin-top: 40px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>

        <van-popup v-model="genderPicker" position="bottom">
            <van-picker
                    show-toolbar
                    :columns="genderList"
                    @cancel="genderPicker = false"
                    @confirm="genderConfirm"
            />
        </van-popup>

        <van-popup v-model="deptPicker" position="bottom">
            <van-picker
                    show-toolbar
                    :columns="deptList"
                    @cancel="deptPicker = false"
                    @confirm="deptConfirm"
            />
        </van-popup>

        <van-popup v-model="educationPicker" position="bottom">
            <van-picker
                    show-toolbar
                    :columns="educationList"
                    @cancel="educationPicker = false"
                    @confirm="eduConfirm"
            />
        </van-popup>

        <van-popup v-model="professionPicker" position="bottom">
            <van-picker
                    show-toolbar
                    :columns="professionList"
                    @cancel="professionPicker = false"
                    @confirm="professionConfirm"
            />
        </van-popup>

        <van-popup v-model="birthdayPicker" position="bottom">
            <van-datetime-picker
                    :value="currentDate"
                    label="出生日期"
                    type="date"
                    :min-date="minDate"
                    :max-date="currentDate"
                    :filter="reverse"
                    @cancel="birthdayPicker = false"
                    @confirm="birthdayConfirm"
            />
        </van-popup>

        <van-popup v-model="cardPicker" position="bottom">
            <van-datetime-picker
                    label="证件有效期"
                    type="date"
                    :min-date="new Date()"
                    :max-date="new Date(2099,12,31)"
                    @cancel="cardPicker = false"
                    @confirm="cardEndConfirm"
            />
        </van-popup>
        <van-dialog
                title="请输入您的职业"
                v-model="professionOther"
                show-cancel-button
                :beforeClose="dialogConfirm"
        >
            <van-field
                    v-model="profession"
                    label="职业"
                    placeholder="请输入职业"
            />

        </van-dialog>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                left:'返回',
                btnName:'下一步',
                disabled:false,
                showCheckbox:true,
                minDate: new Date(1920, 0, 1),
                currentDate: new Date(),
                info:'',
                status:0, // 0新签  1续签
                genderList:['男', '女'],  // 性别
                educationList:[], // 学历
                professionList:[], // 职业
                deptList: [], // 部门
                genderPicker: false,
                birthdayPicker: false,
                educationPicker: false,
                cardPicker: false,
                deptPicker: false,
                professionPicker: false,
                professionOther:false, // 职业选择其它是需要手动输入
                profession:'',
                dept:'',
                exam_img:'/images/index/mediator/cert.jpg',
                error:'',
                // edu_img: [],
                // edu_degree_img: [],
                form:{
                    name:'', // 姓名
                    sex:'', // 性别
                    birthday:'', // 出生日期
                    zjbh:'', // 证件号码
                    sfz_address:'', // 证件地址
                    sfz_date_end:'', // 证件有效期

                    address:'', // 联系地址
                    postal_code:'', // 邮政编码
                    email:'', // 邮箱
                    dept_id:'', // 所属部门

                    edu_background:'', // 学历
                    profession:'', // 职业
                    manager_number:'', // 经理客户号
                    is_exam:1, // 从业资格考试
                    exam_img:'', // 从业资格图片
                    exam_number:'', // 从业资格图片
                    // edu_img:'', //  学历图片
                    // edu_degree_img:'', //  学位图片
                    func:'perfectInformation'

                }
            }
        },
        watch:{
            zjbh(val) {
                if(this.disabled) {
                    this.form.zjbh = val.replace(val.substring(6,14),"********");
                    return false;
                }
                let len = val.length;
                let card = '';
                if(len >= 18) {
                    card = val.substring(0, 18);
                    this.form.zjbh = card;
                    Vue.api.checkIdCard(card).then(res => {
                        this.error = '';
                    }).catch(error => {
                        this.$toast.fail(error);
                        this.error = error;
                    });
                }
            }
        },
        props: {
            data: {
                type: String,
                default: ''
            },
            readonly:{
                type: String,
                default: ''
            }
        },
        vuerify: {
            'form.name': {
                test: /\S{2,}/,
                message: '必填'
            },
            'form.sex': {
                test: /^[\s|\S]+$/,
                message: '必填'
            },
            'form.birthday': {
                test: /\w{1,}/,
                message: '必填'
            },
            'form.zjbh': {
                test: /^[\S|\s]{18}$/,
                message: '必填'
            },
            'form.sfz_address': {
                test: /^.*[^\d].*$/,
                message: '请填写正确的证件地址'
            },
            'form.sfz_date_end': {
                test: /\w{1,}/,
                message: '必填'
            },
            'form.postal_code':{
                test: /\d{6}/,
                message: '请输入正确的邮编'
            },
            'form.email' : {
                test: /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/,
                message: '请输入正确的邮箱'
             },
            'form.address': {
                test: /^.*[^\d].*$/,
                message: '请填写正确的地址'
            },
            'form.dept_id': {
                test: /\w{1,}/,
                message: '必填'
            },
            'form.edu_background': {
                test: /^[\s|\S]+$/,
                message: '必填'
            },
            'form.profession': {
                test: /^[\s|\S]+$/,
                message: '必填'
            },
            'form.manager_number': {
                test: /\w{1,}/,
                message: '必填'
            },
            // 'form.edu_img': {
            //     test: /^[\S]+$/,
            //     message: '必填'
            // },
        },
        computed: {
            errors () {
                return this.$vuerify.$errors
            },
            zjbh(){
                return this.form.zjbh;
            }
        },
        methods: {
            reverse(type, options){
                if(type === 'year') {
                    return options.reverse();
                }
                return options;
            },
            afterRead(file, detail) {
                let type = detail.name;
                Vue.api.uploadFile(file.file, 'exam_img').then(res => {
                    if(type === 'exam_img') {
                        this.exam_img = file.content;
                        this.form.exam_img = res.path;
                    } else if(type === 'edu_img') {
                        this.edu_img[0].url = file.content;
                        this.form.edu_img = res.path;
                    } else if(type === 'edu_degree_img') {
                        this.edu_degree_img[0].url = file.content;
                        this.form.edu_degree_img = res.path;
                    }
                }).catch(error => this.$toast(error));
                return true;
            },
            beforeRead(file) {
                if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
                    this.$toast('请上jpg或者png图片');
                    return false;
                }
                return true;
            },
            next(){ // 下一步
                if(this.readonly === '1') {
                    history.go(-1);
                    return false;
                }
                if(this.error) {
                    this.$toast.fail(this.error);
                    return false;
                }
                if(this.showCheckbox ) {
                    this.form.is_exam = 1;
                } else {
                    this.form.is_exam = 0;
                }
                let verifyList = [
                    'form.name','form.sex','form.birthday','form.zjbh','form.sfz_address',
                    'form.sfz_date_end','form.address','form.dept_id','form.edu_background',
                    'form.profession','form.manager_number'
                ];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast('信息必填');
                    return false;
                }
                if(this.showCheckbox && !this.form.exam_img) {
                    this.$toast('从业资格证书图片必须上传');
                    return false;
                }

                if(this.showCheckbox && !this.form.exam_number) {
                    this.$toast('从业资格证书编号必须填写');
                    return false;
                }

                // 判断是否过期 大于0表示过期
                if(new Date().getTime()-new Date(this.form.sfz_date_end).getTime() > 0) {
                    this.$toast.fail("您的身份证已过期");
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    Vue.utils.next();
                }).catch(error => {this.$toast(error)});
            },
            genderConfirm(value, detail){ //性别 确认
                this.form.sex = value;
                this.genderPicker = false;
            },
            birthdayConfirm(value) { //出生日期确认
                this.form.birthday = Vue.utils.getDate(value);
                this.birthdayPicker = false;
            },
            cardEndConfirm(value) { // 身份证有效期
                this.form.sfz_date_end = Vue.utils.getDate(value);
                this.cardPicker = false;
            },
            deptConfirm(value) { // 部门
                this.dept = value.text;
                this.form.dept_id = value.id
                this.deptPicker = false;
            },
            eduConfirm(value) { // 学历
                this.form.edu_background = value;
                this.educationPicker = false;
            },
            professionConfirm(value){
                this.professionPicker = false;
                if(value === '其他') {
                    this.professionOther = true;
                } else {
                    this.form.profession = value;
                }
            },
            dialogConfirm(action, done) {
                if (action === 'confirm') {
                    this.form.profession = this.profession;
                } else {
                    this.profession = '';
                }
                done();
            }
        },
        created: function () {
            Vue.api.getDeptList().then(res => { // 部门列表
                this.deptList = res;
            });
            Vue.api.getDistList('education').then(res => { //教育列表
                this.educationList = res;
            });
            Vue.api.getDistList('profession').then(res => { //职业列表
                this.professionList = res;
            });
            let info = JSON.parse(this.data);
            let user = info.data;
            this.form.name = user.name;
            this.form.sex = user.sex;
            this.form.birthday = user.birthday;
            this.form.zjbh = user.zjbh;
            this.form.sfz_address = user.sfz_address;
            this.form.sfz_date_end = user.sfz_date_end;
            if(info.status === 1) { // 续签
                this.form.address = user.address;
                this.form.postal_code = user.postal_code;
                this.form.email = user.email;
                this.form.edu_background = user.edu_background;
                this.form.profession = user.profession;
                this.form.manager_number = user.manager_number;
                this.form.dept_id = user.dept_id;
                this.dept = user.dept;
                if (user.is_exam) {
                    this.showCheckbox = true;
                    if(user.exam_img_base64) {
                        this.form.exam_img = user.exam_img;
                        this.exam_img = user.exam_img_base64
                    }
                    this.form.exam_number = user.exam_number;
                } else {
                    this.form.is_exam = 0;
                }
            }
            if(this.readonly === '1') {
                this.disabled = true;
                this.dept = info.data.dept;
                this.btnName = '返回';
                this.left = '';
            }
        },
        mounted:function()  {

        }
    }
</script>

<style scoped>

</style>