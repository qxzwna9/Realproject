<template>
    <div class="brand-info">
        <h1>สินค้าเสื้อผ้าแบรนด์</h1>
        <div class="products">
            <div class="product" v-for="item in products" :key="item.id">
                <img :src="item.image" :alt="item.name" class="product-image" />
                <h2 class="product-name">{{ item.name }}</h2>
                <p class="product-desc">{{ item.description }}</p>
                <p class="product-price">ราคา: {{ item.price }} บาท</p>
            </div>
        </div>
    </div>
</template>

<script setup>
const products = [
    {
        id: 1,
        name: 'เสื้อยืดแบรนด์ A',
        description: 'เสื้อยืดคุณภาพดี สวมใส่สบาย',
        price: 590,
        image: 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80'
    },
    {
        id: 2,
        name: 'กางเกงยีนส์แบรนด์ B',
        description: 'กางเกงยีนส์ดีไซน์ทันสมัย',
        price: 1290,
        image: 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?auto=format&fit=crop&w=400&q=80'
    },
    {
        id: 3,
        name: 'เสื้อเชิ้ตแบรนด์ C',
        description: 'เสื้อเชิ้ตเนื้อผ้าดี เหมาะกับทุกโอกาส',
        price: 990,
        image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80'
    }
]
</script>

<style scoped>
.brand-info {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}
.products {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}
.product {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    padding: 1rem;
    width: 240px;
    text-align: center;
}
.product-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
}
.product-name {
    margin: 1rem 0 0.5rem;
    font-size: 1.2rem;
}
.product-desc {
    color: #555;
    margin-bottom: 0.5rem;
}
.product-price {
    color: #1976d2;
    font-weight: bold;
}
</style>