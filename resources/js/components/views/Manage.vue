<template>
    <div class="manage">
        <div class="main py-2" v-if="$gate.isActive()">
            <div class="container">
              <!-- <h1></h1> -->
              <Sidenav v-if="$gate.isActive()" />
              <SidenavMobile class="m-see" v-if="good" :m_see="m_see" @close-nav="m_see = 'display: none !important;';"/>

              <button type="button" name="button" class="btn m-see text-orange" @click="good = true;  m_see = 'display: block;';"><span class="fas fa-bars"></span>&nbsp;Menu</button>
                <router-view />
            </div>
        </div>
        <div class="container" v-if="!$gate.isActive()">
            <NoActive />
        </div>
    </div>
</template>

<script>
    import NoActive from '../complements/NoActive'
    import Sidenav from '../complements/Sidenav'
    import SidenavMobile from '../complements/SidenavMobile'
    export default {
      data: () => ({
          good: false,
          m_see: '',
      }),
        components: {
            Sidenav, NoActive, SidenavMobile
        },
    }
</script>

<style lang="scss" scoped>
    .main {
        @media (min-width: 768px) {
            margin-left: 180px;
        }
    }
    @media screen and (min-width: 768px) {
      .m-see {
        display: none !important;
      }
    }
</style>
