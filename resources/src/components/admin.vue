<script>
import axios from "axios";
export default {
  data() {
    return {
      slName: null,
      bkpc: "",
      bkpcs: ["本一批", "专项计划", "本二批", "专科批"],
      bxxz: "",
      bxxzs: ["公办", "民办", "中外合办"],
      yxszd: "",
      yxszds: [
        "河南", "等"
      ],
      "bxts": "",
      bxtss: [
        "985", "211", "双一流", "国家重点", "示范高中", "省重点"
      ],
    };
  },
  created() {
   
  },
  methods: {
    save() {
        axios.post("http://47.92.38.81/api/add_school", {}).then((res) => {

        });
      this.$message("已经保存！");
    },
  },
};
</script>

<template>
  <el-container style="width: 60%; margin: auto">
    <el-main>
      <el-row :gutter="20">
        <el-col :span="6">学校名字: </el-col>
        <el-col :span="6">
          <el-input placeholder="请输入内容" v-model="slName" clearable>
          </el-input>
        </el-col>
        <el-col :span="6" style="text-align: left"> </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="6">报考批次：</el-col>
        <el-col :span="10"> <div  style="text-align: left">
    <el-radio-group v-model="bkpc" size="medium">
      <el-radio-button v-for="i in bkpcs" :label="i" :key="i" ></el-radio-button>
    </el-radio-group>
  </div></el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="6">办学性质: </el-col>
        <el-col :span="10"> <div  style="text-align: left">
    <el-radio-group v-model="bxxz" size="medium">
      <el-radio-button v-for="i in bxxzs" :label="i" :key="i" ></el-radio-button>
    </el-radio-group>
  </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="6">院校所在地: </el-col>
        <el-col :span="6"><div  style="text-align: left">
    <el-radio-group v-model="yxszd" size="medium">
      <el-radio-button v-for="i in yxszds" :label="i" :key="i" ></el-radio-button>
    </el-radio-group>
  </div>
        </el-col>
      </el-row>
       <el-row :gutter="20">
        <el-col :span="6">办学特色: </el-col>
        <el-col :span="14">
         <div  style="text-align: left">
    <el-radio-group v-model="bxts" size="medium">
      <el-radio-button v-for="i in bxtss" :label="i" :key="i" ></el-radio-button>
    </el-radio-group>
  </div>
        </el-col>
      </el-row>
      <div>
        <el-row :gutter="20" style="padding: 20px">
          <el-col :span="20" style="text-align: center"
            ><el-button @click="save" type="danger">保存</el-button
            ><el-button type="primary"
              ><router-link target="_blank" to="/list"
                >查看</router-link
              ></el-button
            ></el-col
          >
        </el-row>
      </div>
    </el-main>
  </el-container>
</template>
<style>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.el-col {
  border-radius: 4px;
  text-align: right;
  line-height: 29px;
  color: #000;
}
.bg-purple-dark {
  background: #99a9bf;
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  background: #e5e9f2;
}
.grid-content {
  border-radius: 4px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
.model-line {
  border: 1px solid #ccc;
  padding-top: 25px;
}
a {
  text-decoration: auto;
  color: #fff;
}
</style>