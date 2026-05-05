<div class="relative w-full h-[500px] lg:h-[600px] overflow-hidden group bg-gray-900"
     x-data="{
        activeSlide: 0,
        slides: [
            'https://res.cloudinary.com/davfujasj/image/upload/v1764930307/Blogpost_Elden_Ring_Nightreign_Bosses_Guide_Game_Art_Image_Cover_mnt0cy.webp',
            'https://res.cloudinary.com/davfujasj/image/upload/v1764930883/zenless-zone-zero-czvdx_krhhcg.jpg',
            'https://res.cloudinary.com/davfujasj/image/upload/q_auto:best/v1764929476/wuthering-waves-28-1_pvz4jc.jpg',
            'https://res.cloudinary.com/davfujasj/image/upload/q_auto:best/v1764929712/genshin-impact-mo-rong-mondstadt_wubrod.jpg'
        ],
        timer: null,
        init() {
            this.startTimer();
        },
        startTimer() {
            this.timer = setInterval(() => {
                this.next();
            }, 8000);
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slides.length;
        }
     }"
     x-init="init()">
    
    <template x-for="(slide, index) in slides" :key="index">
        <div class="absolute inset-0 w-full h-full bg-cover bg-center transition-opacity duration-1000 ease-in-out"
             x-show="activeSlide === index"
             x-transition:enter="transition ease-out duration-1000"
             x-transition:enter-start="opacity-0 transform scale-105"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-1000"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-105"
             :style="`background-image: url('${slide}');`">
        </div>
    </template>

    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-miku-900/30 mix-blend-multiply pointer-events-none"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 to-transparent pointer-events-none"></div>

    <div class="relative z-10 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
        <div class="max-w-2xl animate-fade-in-up"> 
            
            <div class="inline-flex items-center gap-2 bg-miku-500/20 border border-miku-500/50 rounded-full px-3 py-1 mb-4 backdrop-blur-sm">
                <span class="flex h-2 w-2 relative justify-center items-center">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-miku-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-miku-500"></span>
                </span>
                <span class="text-miku-300 text-xs font-bold uppercase tracking-widest">Top Trending 2025</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight drop-shadow-lg">
                Thế Giới Game <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-miku-400 to-blue-400 filter drop-shadow-[0_0_10px_rgba(57,197,187,0.5)]">
                    Đa Sắc Màu
                </span>
            </h1>
            
            <p class="text-lg text-gray-300 mb-8 leading-relaxed font-medium drop-shadow-md">
                Trải nghiệm kho game khổng lồ. Tải xuống nhanh chóng, bảo mật tuyệt đối.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="#" class="group relative inline-flex items-center justify-center px-8 py-3 font-bold text-white bg-miku-500 rounded-full overflow-hidden shadow-lg shadow-miku-500/30 transition-all duration-300 hover:bg-miku-600 hover:scale-105 hover:shadow-miku-500/50">
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-miku-400 to-miku-600 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                    <span class="relative flex items-center gap-2">
                        Khám Phá Ngay <i class="fa-solid fa-rocket group-hover:animate-bounce"></i>
                    </span>
                </a>
                
                <a href="#" class="inline-flex items-center justify-center px-8 py-3 font-bold text-white border-2 border-gray-400/50 bg-gray-900/30 backdrop-blur-sm rounded-full transition-all duration-300 hover:bg-white hover:text-gray-900 hover:border-white">
                    Xem Cộng Đồng <i class="fa-brands fa-discord ml-2"></i>
                </a>
            </div>

            <div class="flex gap-2 mt-8">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index; clearInterval(timer); startTimer();" 
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'bg-miku-500 w-8' : 'bg-gray-500 hover:bg-gray-400'">
                    </button>
                </template>
            </div>
            
        </div>
    </div>
</div>