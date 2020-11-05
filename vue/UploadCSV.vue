<template>
    <div>
        <div v-if="!submitting" class="text-lg-center">
            <input type="file" id="uploaddata" style="display: none" v-on:change="handleFileChange" multiple/>
            <v-btn color="primary" dark large v-on:click="uploadcsv">Upload Data(csv/excel)</v-btn>
        </div>
        <div v-else>

            <div class="text-lg-center">
                <input type="file" id="miduploaddata" style="display: none" v-on:change="MidhandleFileChange" multiple/>
                <v-btn color="primary" dark large v-on:click="miduploadcsv">Add New Data(csv/excel)</v-btn>
            </div>
            <h2 class="text-lg-center">Column Headings & Data Info</h2>
            <p class="text-lg-center">Row Count: <strong>{{tableData.length}}</strong> ,Column Count: <strong>8</strong> </p>
            <template>
                <v-card>
                    <v-tabs
                            v-model="tab"
                            background-color="primary"
                            dark
                    >
                        <v-tab
                                v-for="name in filenames.length"
                                :key="name"
                        >
                            {{filenames[name-1] }}
                        </v-tab>
                    </v-tabs>
                </v-card>

                <v-card  style="display: block;height: 50px;padding-right: 17px;">
                    <table style="width: 100%;border: solid 1px gray">
                        <thead style="width: 100%">
                        <tr style="height: 49px">
                            <th width="8%" style="min-width: 59px">
                            </th>
                            <th width="19%" style="min-width: 190px;font-size: 12px;">
                                Name
                            </th>
                            <th v-for="header in headers" :key="header.text"
                                width="12%"
                                style="font-size: 12px;"
                            >
                                {{ header.text }}
                            </th>
                        </tr>
                        </thead>
                    </table>
                </v-card>
                <template>
                    <v-tabs-items v-model="tab">
                        <v-tab-item
                                v-for="len in filelength.length"
                                :key="len"
                        >
                            <v-card  style="display: block;height: 300px;overflow-y: scroll;">
                                <table style="width: 100%;border: solid 1px #d8d8d8">
                                    <tbody >
                                    <tr v-for="item in tableData.slice(filelength[len-1].a,filelength[len-1].a+filelength[len-1].b)" :key="item.name+item.id" @click="changeselectedcol(item.id,item)">
                                        <td width="8%" style="min-width: 59px;font-size: 11px;">
                                            <v-radio-group v-model="selectedCol" name="rowSelector" :mandatory="false" class="mt-0 " style="display: flex;justify-content: center">
                                                <v-radio :value="item.id" />
                                            </v-radio-group>
                                        </td>
                                        <td class="text-xs-left" width="19%" style="min-width: 190px;font-size: 11px;">{{ item.name }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{ item.min }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{ item.max }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{ item.mode }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{ parseInt(item.mean) }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{parseInt(item.median)  }}</td>
                                        <td class="text-xs-right" width="12%" style="font-size: 11px;">{{ item.range }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </v-card>
                        </v-tab-item>
                    </v-tabs-items>
                </template>
            </template>
            <template>
                <v-card >
                    <line-chart :chartdata="chartdata" :options="options" :height="200"/>
                </v-card>
            </template>
        </div>
    </div>
</template>

<script>
    import LineChart from './LineChart.js'

    export default {
        name: "UploadCSV",
        components: {
            LineChart
        },

        computed: {
            selectedCol: {//selected Column from data table
                get: function () {
                    return this.$store.getters.getSelectedCol;
                },
                set: function (v) {
                    this.$store.commit('setSelectedCol', v);
                }
            },
            allColSettings: {
                get: function () {
                    return this.$store.getters.getAllColsettings;
                },
                set: function (v) {
                    this.$store.commit('setAllColumnSetting', v);
                }
            },
            tableData: {//get and set table data
                get: function () {
                    return this.$store.getters.getTableData;
                },
                set: function (v) {
                    this.$store.commit('setTableData', v);
                }
            },
            rawColsData: {//column data
                get: function () {
                    return this.$store.getters.getRawCols;
                },
                set: function (v) {
                    this.$store.commit('setRawColsData', v);
                }
            },
            submitting: {
                get: function () {
                    return this.$store.getters.getTableViewStatus;
                },
                set: function (v) {
                    this.$store.commit('setTableViewStatus', v);
                }
            },
            filenames: {
                get: function () {
                    return this.$store.getters.getFileNames;
                },
                set: function (v) {
                    this.$store.commit('setFileNames', v);
                }
            },
            filelength: {
                get: function () {
                    return this.$store.getters.getFileLengths;
                },
                set: function (v) {
                    this.$store.commit('setFileLengths', v);
                }
            },
        },
        data() {
            return {
                chartdata: {
                    datasets: [
                        {
                            label: 'Data View',
                            backgroundColor: '#f87979',
                            data: []
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                },
                error: false,
                success: false,
                fileinput: null,
                filelen: 0,
                filedata:[],
                rows: [],
                cols: [],
                data: [],
                selected: 0,
                pagination: {
                    sortBy: 'name'
                },
                tab: 0,
                headers: [
                    {
                        text: 'Min',
                        value: 'min'
                    },
                    {
                        text: 'Max',
                        value: 'max'
                    },
                    {
                        text: 'Mode',
                        value: 'mode'
                    },
                    {
                        text: 'Mean',
                        value: 'mean'
                    },
                    {
                        text: 'Median',
                        value: 'median'
                    },
                    {
                        text: 'Range',
                        value: 'range'
                    }
                ],

            }
        },
        methods: {
            uploadcsv() {
                let elem = document.getElementById('uploaddata');
                elem.click();
            },
            miduploadcsv(){
                let elem = document.getElementById('miduploaddata');
                elem.click();
            },
            changeselectedcol(id,item) {//update selected column data
                let resdata = [];
                let reslabel =[];
                if(item && item.data.length){
                    item.data.forEach((_item)=>{
                        let miditem ={x:_item,y:0,r:5};
                        resdata.push(miditem);
                        if(item.min === _item){
                            reslabel.push('Min');
                        }
                        else if(item.max === _item){
                            reslabel.push('Max');
                        }
                        else if(item.mode === _item){
                            reslabel.push('Mode');
                        }
                        else if(item.mean === _item){
                            reslabel.push('Mean');
                        }
                        else if(item.median === _item){
                            reslabel.push('Median');
                        }
                        else{
                            reslabel.push('');
                        }
                    });
                }

                this.chartdata=
                    {
                        datasets:[
                            {
                                label: item.name,
                                backgroundColor: '#f87979',
                                data: resdata
                            }
                        ]
                    };
                this.selected = id;
                this.selectedCol = id;

            },
            handleFileChange() {//handle file select
                const input = document.getElementById('uploaddata');
                const files = input.files;
                if(files){
                    this.filelen = files.length;
                    for(let i = 0; i <files.length ; i ++){
                        var reader = new FileReader();
                        reader.onload = (e) =>{
                            this.fileinput = e.target.result;
                            this.checkfile(e.target.result,false);
                        }
                        if(files[i] && files[i].name){
                            this.filenames.push(files[i].name);
                        }
                        reader.readAsText(files[i]);
                    }
                }
            },
            MidhandleFileChange() {
                const input = document.getElementById('miduploaddata');
                const files = input.files;
                if(files){
                    var midfilelen = files.length;
                    this.filelen +=midfilelen;
                    for(let i = 0; i <files.length ; i ++){
                        var reader = new FileReader();
                        reader.onload = (e) =>{
                            this.fileinput = e.target.result;
                            this.checkfile(e.target.result,true);
                        }
                        if(files[i] && files[i].name){
                            this.filenames.push(files[i].name);
                        }
                        reader.readAsText(files[i]);
                    }
                }
            },
            checkfile(file,status){
                this.filedata.push(file);
                if(this.filelen && this.filelen === this.filedata.length){
                    if(status){
                        this.rows=[];
                        this.cols = [];
                        this.data = [];
                        this.tableData = [];
                        this.filelength = [];
                    }

                    this.makerowcols(this.filedata,status);
                }
            },
            makerowcols(data,status) {
                let rows = [];
                let resrow = [];
                if(data && data.length){
                    data.map((_input)=>{
                        rows = _input.split("\n");
                        resrow = [];
                        if (rows && rows.length) {
                            rows.forEach((row) => {
                                if(row){
                                    let m = row.split(",");
                                    resrow.push(m);
                                }
                            });
                        }
                        if(rows && resrow && resrow.length){
                            this.rows = [...this.rows,...resrow];
                        }
                        this.makedata(resrow);
                    });
                }
                if(!status){
                    this.changesubmit();
                }

            },
            getmode(input) {
                let count = [];
                for (let i = 0; i < input.length; i++) {
                    count[input[i]]++;
                }
                let index = count.length - 1;
                for (let i = count.length - 2; i >= 0; i--) {
                    if (count[i] >= count[index])
                        index = i;
                }
                return index;
            },
            makedata(getrows) {//make tree data from uploaded file data
                const rows = getrows;
                const cols = getrows.length ? getrows[0] : [];
                let rescol = [];

                let midsetting = [];
                if (cols && cols.length) {
                    cols.forEach((col, index) => {
                        midsetting.push({
                            color: 0,
                            geometry: 0,
                            scale: {
                                independent: false,
                                parent: false,
                                X: [0, 5],
                                Y: [0, 5],
                                Z: [0, 5],
                            }
                        });
                        let midcols = [];
                        rows.forEach((row) => {
                            midcols.push(row[index]);
                        });
                        rescol.push(midcols);
                    });
                }
                this.allColSettings = midsetting;
                if (rescol && rescol.length) {
                    let resdata = [];
                    let datanum = this.tableData.length;
                    if(datanum>0){
                        datanum += 1;
                    }
                    rescol.forEach((col, index) => {
                        let name = "";
                        let min = null;
                        let max = null;
                        let mode = 0;
                        let mean = 0;
                        let median = 0;
                        let range = 0;
                        let sum = 0;
                        let len = 0;
                        let mmedian = [];
                        if (isNaN(col[0])) {
                            name = col[0];
                        }
                        col.forEach((item) => {

                            if (item && !isNaN(item)) {

                                if (min === null || parseFloat(min) > parseFloat(item)) {
                                    min = parseFloat(item)
                                }
                                if (max === null || parseFloat(max) < parseFloat(item)) {
                                    max = parseFloat(item);
                                }
                                mmedian.push(parseFloat(item));
                                sum += parseFloat(item);
                                len++;
                            }
                        });
                        if (len) {
                            mean = parseFloat(sum / len);
                        }
                        if (max !== null && min !== null) {
                            range = max - min;
                        }
                        mmedian.sort((a, b) => {
                            return a - b
                        });
                        if (mmedian.length % 2 === 0) {
                            median = parseFloat(parseFloat(mmedian[mmedian.length / 2]) + parseFloat(mmedian[
                            mmedian.length / 2 - 1]) / 2);
                        } else {
                            median = parseFloat(mmedian[(mmedian.length - 1) / 2]);
                        }
                        mode = this.getmode(mmedian);
                        let mdata = {
                            name: name,
                            min: min,
                            max: max,
                            mode: mode,
                            mean: mean,
                            median: median,
                            range: range,
                            data: mmedian,
                            id: index+datanum,
                        };
                        if (min !== null && max !== null && len) {
                            resdata.push(mdata);
                        }
                    });
                    this.filelength.push({a:this.data.length,b:resdata.length});
                    this.data = [...this.data,...resdata];
                    this.tableData = [...this.tableData,...resdata];
                    if(this.tableData && this.tableData.length){
                        let datalen1 = this.tableData && this.tableData[0] && this.tableData[0].data ? this.tableData[0].data.length :0;
                        let datalen2 = resdata && resdata[0] && resdata[0].data ? resdata[0].data.length :0;
                        let datalen = datalen1 > datalen2 ? datalen2 : datalen1;
                        let midtabledata = [...this.tableData];
                        let retabledata=[];
                        midtabledata.map((_item)=>{
                           let  item = {
                               ..._item,
                               data: datalen ? _item.data.slice(0,datalen):_item.data,
                           };
                           retabledata.push(item);
                        });
                        this.data=[...retabledata];
                        this.tableData=[...retabledata];
                    }
                    this.changeselectedcol(0,this.data[0]);

                }
                this.cols = [...this.cols,...rescol];
                this.rawColsData = [...this.cols,...rescol];
            },
            changesubmit() {
                this.submitting = !this.submitting;
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            }

        }

    }
</script>

<style scoped>
    table, td, th {
        border: 1px solid #d8d8d8;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }

</style>
