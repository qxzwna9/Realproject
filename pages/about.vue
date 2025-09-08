<template>
  <div class="about-bg">
    <v-container class="py-16">
      <v-row justify="center">
        <v-col cols="12" md="8">
          <v-card class="card-glassmorphism pa-6 pa-md-8">
            <h1 class="page-title text-center mb-6">เกี่ยวกับ ELVURE SHOP</h1>
            <v-divider class="mb-6"></v-divider>
            <div class="about-content white--text">
              <p>
                ELVURE SHOP ก่อตั้งขึ้นจากความหลงใหลในแฟชั่นและศิลปะบนเสื้อผ้า เราเชื่อว่าเสื้อยืดเป็นมากกว่าแค่เครื่องแต่งกาย แต่เป็นพื้นที่สำหรับแสดงออกถึงตัวตน ความคิดสร้างสรรค์ และเรื่องราวต่างๆ
              </p>
              <p>
                ทีมงานของเราประกอบด้วยนักออกแบบรุ่นใหม่ที่มีไฟและรักในสิ่งเดียวกัน เราใส่ใจในทุกรายละเอียด ตั้งแต่การเลือกเนื้อผ้าคุณภาพดี การออกแบบลายกราฟิกที่ไม่เหมือนใคร ไปจนถึงกระบวนการสกรีนที่ได้มาตรฐาน เพื่อให้คุณมั่นใจได้ว่าจะได้รับสินค้าที่ดีที่สุดจากเรา
              </p>
              <h2 class="subtitle mt-6 mb-2">ภารกิจของเรา</h2>
              <p>
                ภารกิจของเราคือการสร้างสรรค์เสื้อผ้าที่ทุกคนสามารถใส่ได้ในทุกๆ วัน แต่ยังคงไว้ซึ่งความโดดเด่นและมีสไตล์ เราต้องการเป็นส่วนหนึ่งในการสร้างความมั่นใจและแรงบันดาลใจให้กับผู้คนผ่านผลงานการออกแบบของเรา
              </p>
              
              <h2 class="subtitle mt-8 mb-4">ช่องทางการติดต่อ</h2>
              <v-list color="transparent" dark>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon color="primary">mdi-email</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>elvureshop@gmail.com</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon color="primary">mdi-phone</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>08x-xxx-xxxx</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon color="primary">mdi-facebook</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>facebook.com/elvureshop</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <div class="text-center mt-8">
                <v-btn color="primary" large to="/shirt">
                  เลือกชมสินค้าของเรา
                </v-btn>
              </div>
            </div>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
export default {
  head() {
    return {
      title: 'เกี่ยวกับเรา'
    }
  }
}
</script>

<style scoped>
.about-bg {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(135deg, #111827 0%, #1e293b 100%);
  padding-top: 60px; /* Adjust for app bar */
}

.page-title {
  color: #FFFFFF;
  font-weight: 700;
  font-size: 2.5rem;
  text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.card-glassmorphism {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 16px;
}

.about-content p {
  font-size: 1.1rem;
  line-height: 1.8;
  margin-bottom: 16px;
}

.subtitle {
  font-size: 1.5rem;
  font-weight: 600;
  color: #F97316; /* Accent color */
}

.v-list-item__title {
  color: #E5E7EB;
}
</style>