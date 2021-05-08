<template>

<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{title}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tbody><tr>
                  <th style="width: 10px">#</th>
                  <th>Task</th>
                  <th style="width: 40px">发布</th>
                  <th style="width: 40px">修改</th>
                  <th style="width: 40px">删除</th>
                </tr>
                <tr v-for='(b,index) in jokes'>
                  <td>{{index}}.</td>
                  <td>{{b.title||b.createdate}}</td>
                  <td>
                    <a v-if="b.published" ><span class="badge">已发布</span></a>
                    <a v-else  @click="publish(b._id)"><span class="badge bg-blue">发布</span></a>
                  </td>
                  <td><a v-bind:href="'/admin/editjoke?jokeid='+b._id"><span class="badge bg-blue">修改</span></a></td>
                  <td @click='deletejoke(b._id)'><span class="badge bg-red">删除</span></td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">«</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">»</a></li>
              </ul>
            </div>
          </div>

</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      jokes: null,
      title: '我的所有Jokes',
      jokeurl: '/jokes?sort=-_id',
      jokedel: '/jokes'
    }
  },
  created () {
    this.getJokes()
    window.menuvue.$options.data().menus[1].vclass = 'treeview active'
  },

  methods: {
    getJokes () {
      var that = this
      axios.get(this.jokeurl)
        .then(function (response) {
          that.jokes = response.data
        })
    },
    publish (id) {
      axios.put(this.jokedel + '/' + id, { published: 1 })
      window.location.reload()
    },
    deletejoke (id) {
      axios.delete(this.jokedel + '/' + id)
      window.location.reload()
    },
    editjoke (id) {
      window.location.replace('admin/editjoke?jokeid=' + id)
      window.location.reload()
    }
  },
  components: {
  }
}
</script>
<style>
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    text-align: left;
}
</style>
