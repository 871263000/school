<template>
  <el-container style="width: 800px; margin: auto">
    <el-header style="text-align: center; font-size: 20px"
      ><h1>{{ slNames[slName] }}</h1></el-header
    >
    <el-row :gutter="20">
      <el-col :span="15"><div style="height: 80px"></div></el-col>
      <el-col :span="4" style=""
        ><el-select v-model="slName" filterable placeholder="请选择">
          <el-option
            v-for="(item, index) in slNames"
            :key="index"
            :label="item"
            :value="index"
          >
          </el-option> </el-select
      ></el-col>
      <el-col :span="4"
        ><el-input v-model="searchKey" placeholder="搜索内容"></el-input
      ></el-col>
    </el-row>
    <el-tabs v-model="lwk" type="card" @tab-click="handleClick">
      <el-tab-pane label="理科" name="lk">
        <el-table :data="searchKey ? searchZyTables :zyTables" height="500" border style="width: 100%">
          <el-table-column
            prop="specialized"
            label="专业"
            width="300"
          ></el-table-column>
          <el-table-column prop="name" label="年份" width="500">
            <el-table-column
              :prop="i"
              :label="i"
              v-for="i in years"
              :key="i"
              :width="500 / years.length"
            >
            </el-table-column>
          </el-table-column>
        </el-table>
      </el-tab-pane>
      <el-tab-pane label="文科" name="wk">
        <el-table :data="searchKey ? searchZyTables :zyTables" height="500" border style="width: 100%">
          <el-table-column prop="specialized" label="专业" width="300">
          </el-table-column>
          <el-table-column prop="name" label="年份" width="500">
            <el-table-column
              :prop="i"
              :label="i"
              v-for="i in years"
              :key="i"
              :width="500 / years.length"
            >
            </el-table-column>
          </el-table-column>
        </el-table>
      </el-tab-pane>
    </el-tabs>
  </el-container>
</template>
<script>
export default {
  data() {
    return {
      slNames: [],
      slName: 0,
      lwk: "lk",
      years: [],
      zy: [],
      zyTables: [],
      searchKey: "",
      searchZyTables: []
    };
  },
  computed: {
    wkTable() {
      let wkd = [];
      this.slNames.forEach((element, index) => {
        if (index == slName) {
          let ls = this.getLocal();
          ls[slName].office.wk;
        }
      });
    },
    lkTable: {},
  },
  created() {
    let ls = this.getLocal();
    if (ls.length != 0) {
      for (let index = 0; index < ls[0].office.lk[0].year.length; index++) {
        const element = ls[0].office.lk[0].year[index];
        this.years.push(element.y);
      }
    }
    ls.forEach((element) => {
      this.slNames.push(element.name);
    });
    this.getZY(this.slName);
  },
  methods: {
    getLocal() {
      let lsD = localStorage.getItem("school");
      let ld = [];
      if (lsD) {
        ld = JSON.parse(lsD);
      }
      return ld;
    },
    getZY(scName) {
      this.years = [];
      let lsds = this.getLocal();
      let lsd;
      if (lsds.length > 0) {
        lsd = lsds[scName];
        let zyTable;
        this.zyTables = [];
        lsd.office[this.lwk].forEach((element, index) => {
          zyTable = { specialized: element.specialized };
          element.year.forEach((e) => {
            console.log(e);
            if (index == 0) {
              this.years.push(e.y);
            }
            zyTable[e.y] = e.f;
          });
          this.zyTables.push(zyTable);
        });
      }
    },
  },
  watch: {
    slName(n, o) {
      this.getZY(n);
    },
    lwk(n, o) {
      this.getZY(this.slName);
    },
    searchKey(n, o) {
        this.searchZyTables =  this.zyTables.filter( e => {
            if (e.specialized.indexOf(n) != -1) {
                return true; 
            } else {
                return false
            }
        })
        console.log( this.searchZyTables );
    }
  },
};
</script>
<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
