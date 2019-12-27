<template>
    <layout title="完善信息" >
        <div style="margin-top: 60px;">
            <van-divider>基本信息</van-divider>

            <van-cell-group>
                <van-field  placeholder="请输入姓名"  label="姓名"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        required
                        readonly
                        clickable
                        label="性别"
                        placeholder="选择性别"
                        @click="genderPicker = true"
                />
            </van-cell-group>
            <van-cell-group>
                <van-field
                        required
                        readonly
                        clickable
                        label="出生日期"
                        placeholder="出生日期"
                        @click="birthdayPicker = true"
                />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="证件号码"  label="证件号码"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="证件地址"  label="证件地址"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        required
                        readonly
                        clickable
                        label="证件有效期"
                        placeholder="证件有效期"
                        @click="cardPicker = true"
                />
            </van-cell-group>

            <van-divider>详细信息</van-divider>
            <van-cell-group>
                <van-field  placeholder="联系地址"  label="联系地址"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="邮政编码"  label="邮政编码"  />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="邮箱"  label="邮箱"  />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        required
                        readonly
                        clickable
                        label="所属部门"
                        placeholder="所属部门"
                        @click="deptPicker = true"
                />
            </van-cell-group>

            <van-cell-group>
                <van-field
                        required
                        readonly
                        label="合同到期日"
                        placeholder="合同到期日"
                />
            </van-cell-group>
            <van-divider>其它信息</van-divider>

            <van-cell-group>
                <van-field  placeholder="学历"  label="学历"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="职业"  label="职业"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="客户经理号"  label="客户经理号"  required />
            </van-cell-group>

            <van-cell-group>
                <van-switch-cell v-model="switchCell" title="是否通过期货从业资格考试" required />
            </van-cell-group>

            <div style="text-align: center">
                <van-button style="margin-top: 40px;width: 94%" type="info" @click="next()">下一步</van-button>
            </div>
        </div>

        <van-popup v-model="genderPicker" position="bottom">
            <van-picker
                    :columns="genderList"
                    @cancel="genderPicker = false"
            />
        </van-popup>

        <van-popup v-model="deptPicker" position="bottom">
            <van-picker
                    :columns="deptList"
                    @cancel="deptPicker = false"
            />
        </van-popup>

        <van-popup v-model="birthdayPicker" position="bottom">
            <van-datetime-picker
                    label="出生日期"
                    type="date"
                    :min-date="new Date(1950, 1, 1)"
                    :max-date="new Date()"
                    @cancel="birthdayPicker = false"
            />
        </van-popup>

        <van-popup v-model="cardPicker" position="bottom">
            <van-datetime-picker
                    label="证件有效期"
                    type="date"
                    :min-date="new Date()"
                    :max-date="new Date(2099,12,31)"
                    @cancel="cardPicker = false"
            />
        </van-popup>

    </layout>
</template>

<script>
    export default {
        data() {
            return {
                genderList:['男', '女'],
                genderPicker: false,
                birthdayPicker: false,
                cardPicker: false,
                deptPicker: false,
                deptList: ['部门A', '部门B'],
                switchCell: false,
            }
        },
        methods: {
            onChange(picker, value, index) {
                Toast(`当前值：${value}, 当前索引：${index}`);
            },
            next(){ // 下一步
                window.location.href = "/index/mediator/sign";return false;
            }
        }
    }
</script>

<style scoped>

</style>