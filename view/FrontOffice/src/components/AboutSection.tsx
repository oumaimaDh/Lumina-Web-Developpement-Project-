import { motion } from "motion/react";
import { ImageWithFallback } from "./figma/ImageWithFallback";
import { Heart, Users, Smile } from "lucide-react";

export function AboutSection() {
  return (
    <motion.section
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.8, delay: 0.3 }}
      className="relative mt-16"
    >
      {/* Organic blob container */}
      <div className="relative">
        {/* Background organic shape */}
        <div className="absolute inset-0 -z-10">
          <svg viewBox="0 0 1200 600" className="w-full h-full" preserveAspectRatio="none">
            <path 
              d="M 0 100 Q 300 50 600 100 Q 900 150 1200 100 L 1200 500 Q 900 450 600 500 Q 300 550 0 500 Z" 
              fill="#f5f0ed"
              opacity="0.6"
            />
          </svg>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center p-8 md:p-12">
          {/* Text Content */}
          <div className="relative">
            <div className="bg-gradient-to-br from-white to-[#f5f0ed] backdrop-blur-sm rounded-[3rem] p-8 md:p-12 shadow-xl border-2 border-[#8195b8]/10">
              <div className="inline-flex items-center gap-2 mb-6 px-5 py-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white rounded-full text-sm">
                <Heart className="w-4 h-4 fill-white" />
                <span>Who We Are</span>
              </div>
              
              <h2 className="text-[#1e3a5f] mb-4">Your Neighbors, Your Friends 👋</h2>
              
              <div className="space-y-4 text-[#5b7ba4]">
                <p className="leading-relaxed">
                  Lumina is more than a platform – it's a family of passionate people just like you! We believe that when neighbors help neighbors, incredible things happen. ✨
                </p>
                <p className="leading-relaxed">
                  Since our beginning, we've watched friendships blossom, skills grow, and communities transform. Whether you have an hour to spare or a lifetime of experience to share, you're exactly who we're looking for!
                </p>
                <p className="leading-relaxed">
                  No experience needed, no complicated forms – just your enthusiasm and a desire to make someone's day better. That's all it takes to be part of something beautiful. 💙
                </p>
              </div>

              {/* Friendly values */}
              <div className="grid grid-cols-3 gap-4 mt-8">
                <div className="text-center bg-white/60 rounded-2xl p-4">
                  <Users className="w-8 h-8 text-[#5b7ba4] mx-auto mb-2" />
                  <div className="text-[#1e3a5f] text-sm">Inclusive</div>
                </div>
                <div className="text-center bg-white/60 rounded-2xl p-4">
                  <Heart className="w-8 h-8 text-[#5b7ba4] mx-auto mb-2 fill-[#5b7ba4]" />
                  <div className="text-[#1e3a5f] text-sm">Caring</div>
                </div>
                <div className="text-center bg-white/60 rounded-2xl p-4">
                  <Smile className="w-8 h-8 text-[#5b7ba4] mx-auto mb-2" />
                  <div className="text-[#1e3a5f] text-sm">Joyful</div>
                </div>
              </div>
            </div>
          </div>

          {/* Image Grid with Organic Shapes */}
          <div className="relative">
            {/* Main image with organic shape */}
            <div className="relative overflow-hidden rounded-[3rem] shadow-2xl">
              <ImageWithFallback
                src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb21tdW5pdHklMjB2b2x1bnRlZXIlMjBncm91cHxlbnwxfHx8fDE3NjMzMjI3ODF8MA&ixlib=rb-4.1.0&q=80&w=1080"
                alt="Community volunteers"
                className="w-full h-[400px] object-cover"
              />
              {/* Decorative overlay blob */}
              <div className="absolute bottom-0 right-0 w-32 h-32 bg-[#8195b8]/30 rounded-full blur-2xl" />
            </div>

            {/* Floating friendly cards */}
            <div className="absolute -top-4 -right-4 bg-white rounded-3xl p-5 shadow-xl border-4 border-[#8195b8]/20 animate-bounce" style={{ animationDuration: '3s' }}>
              <div className="text-center">
                <div className="text-4xl mb-1">😊</div>
                <div className="text-2xl text-[#1e3a5f]">500+</div>
                <div className="text-xs text-[#5b7ba4]">Smiling Faces</div>
              </div>
            </div>

            <div className="absolute -bottom-4 -left-4 bg-gradient-to-br from-[#6b7da8] to-[#8195b8] text-white rounded-3xl p-5 shadow-xl">
              <div className="text-center">
                <div className="text-3xl mb-1">🎉</div>
                <div className="text-sm opacity-90">Join Our Family!</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </motion.section>
  );
}