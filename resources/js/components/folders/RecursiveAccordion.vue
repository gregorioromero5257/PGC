<template>
<main class="main">
  <!--Accordion wrapper-->
<div class="accordion md-accordion" :id="ids" role="tablist" aria-multiselectable="true">
<template v-for="t in folders"  >

  <template v-if="typeof(t) != 'string'">
    <!-- Accordion card -->
    <div class="card bg-card">

      <!-- Card header -->
      <div :class="'card-header bg-card ' + ((idh == t.id && id_folder != t.id) ? 'heasados' : (idh == t.id && id_folder == t.id) ? 'heasa' : '')" role="tab" :id="'headingOne'+t.id">
        <a :id="'linkOne'+t.id" :class="'link-a ' + (idh == t.id ? 'link-ha' : '')" data-toggle="collapse" :data-parent="'#'+ids" :href="'#collapseOne'+t.id" aria-expanded="true"
        :aria-controls="'collapseOne' +t.id" @click="onHeadingClick($event, t)">
        <h5 :class="'mb-0 fs-card'" :style="'padding: 0 0 0 '+((0.4 + (t.order/10)))+'rem !important;'">
          <img src="/img/Carpeta.png" style="width : 0.8rem;">
          &nbsp;{{t.name}}&nbsp;
          <i :class="'fas fa-angle-down ' +(idh == t.id ? 'expand_caret' : '')" :id="'iconOne'+t.id"></i>
        </h5>
      </a>
    </div>

    <!-- Card body -->
    <template v-if="t.hasOwnProperty('children')">
    <div :id="'collapseOne'+t.id" class="collapse" role="tabpanel" :aria-labelledby="'headingOne'+t.id"
    :data-parent="'#'+ids">
    <div class="card-body pd-cb">
        <RecursiveAccordion @set-data="setData($event)"
                            @set-title="setTitle($event)"
                            @set-id="setId($event)"
                            @clear-component="clearComponent()"
                            :folders="t.children"
                            :ids="t.d"
                            :id_folder="id_folder"
                            :track_folder="track_folder"
                            :folder_id="folder_id"
                            :clean_after_search="clean_after_search"
                             />

    </div>
  </div>
</template>

</div>
<!-- Accordion card -->
</template>

</template>




</div>
<!-- Accordion wrapper -->

</main>
<!--
    <vsa-list>
      <vsa-item v-for="item in folders" :key="item.id" :on-heading-click="onHeadingClick" >
        <vsa-heading >
          <i class="fas fa-folder"></i>&nbsp;{{ item.name }}
        </vsa-heading>

        <template v-if="item.children">
          <vsa-icon>
          <span class="open"></span>
          <span class="close"></span>
        </vsa-icon>
        <vsa-content>
            <RecursiveAccordion  :folders="item.children" @set-data="setData($event)"
            @set-title="setTitle($event)" @set-id="setId($event)" />

        </vsa-content>
      </template>
      <template v-else>
        <vsa-icon>
        <span class="open"></span>
        <span class="close"></span>
      </vsa-icon>
      </template>

      </vsa-item>

    </vsa-list> -->

    <!-- <table>
      <tr v-for="t in folders">
        <td>{{t.name}}</td>
      </tr>
    </table> -->



</template>

<script>


export default {
  name: "RecursiveAccordion",

  props: {
    folders: {
     type: Object,
     required: true
   },
   ids : String,
   id_folder: Number,
   track_folder : Array,
   folder_id : Number,
   clean_after_search : Number,
 },
 data: () => ({
  heasa : 'heasa',
  idh : '',
  collapse : 'collapse',
 }),
 methods: {
  onHeadingClick(data, d) {
    if (this.clean_after_search == 1) {
      this.$emit('clear-component');
    }else {
      this.idh = d.id;

      this.$emit('set-title', d.name);

      this.$emit('set-id', d.id);
      //
      axios.get('/api/folder/' + d.id).then(response => {
        this.$emit('set-data', response.data);
      }).catch(e => {
        console.error(e);
      });
    }
  },

  setData(e){
    this.$emit('set-data', e);
  },

  setTitle(e){
    this.$emit('set-title', e);
  },

  setId(e){
    this.$emit('set-id', e);
  },

  clearComponent(){
    this.$emit('clear-component');
  },

},
mounted() {
  if ((this.track_folder).length > 0) {
    (this.track_folder).forEach((item, i) => {
      document.getElementById("collapseOne" + item).className = "collapse show";
      document.getElementById("headingOne" + item).className = "card-header bg-card heasados";
      document.getElementById("iconOne" + item).className = "fas fa-angle-down expand_caret";
    });
  }
  if (this.folder_id != 0) {
    document.getElementById("headingOne" + this.folder_id).className = "card-header bg-card heasa";
    document.getElementById("linkOne" + this.folder_id).className = "link-a link-ha";
    document.getElementById("iconOne" + this.folder_id).className = "fas fa-angle-down expand_caret";
  }


}
};
</script>
<style media="screen">

.vsa-list {
  margin: 0px 2% 0px 6%;
  --vsa-max-width : 80px;

  --vsa-highlight-color : #384954;
  --vsa-border-width: 0px;

}

.vsa-item {
  margin: 1% 0 1% 0;
  /* border: 1px solid rgba(0,0,0,.125); */
  /* background-color: #f8f9fa!important; */
  /* border-radius: 5px; */
}

.vsa-item__trigger__content {

    font-weight: 700;
    font-size: 0.80rem;

}

.vsa-item__trigger[type="button"], .vsa-item__trigger[type="reset"], .vsa-item__trigger[type="submit"] {

    -webkit-appearance: button;
    border: 1px solid;
    margin: 0px 88px 0px 20px;

}

.vsa-item__heading, .vsa-item__trigger {
  text-align : initial;
}

.pd-cb {
  /* padding: 0 0 0 0.6rem !important; */
  padding: 0 0 0 0rem !important;
}

.bg-card {
  background-color: #f7f7f7;
  border: none;
}

.fs-card {
  font-size: 12px !important;
}

.heasa {
  background-color: #f68e1d !important;
}

.heasados {
  background-color: #979797 !important;
}

a.link-ha {
  color: #fff !important;
}

a.link-a {
  color: #000000;
  text-decoration: none !important;
}


.expand_caret {
    transform: rotate(270deg);
}

.accordion > .card > .card-header {
    margin-bottom: 2px !important;
}

</style>
