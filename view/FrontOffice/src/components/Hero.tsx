import { motion } from "motion/react";
import { Sparkles, Heart, Users } from "lucide-react";

export function Hero() {
  return (
    <motion.section
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.8 }}
      className="relative overflow-hidden mt-8"
    >
      {/* Main organic blob container with warmer gradient */}
      <div className="relative bg-gradient-to-br from-[#6b7da8] via-[#8195b8] to-[#a8b5c9] rounded-[3rem] p-12 md:p-16">
        {/* Decorative blob shapes */}
        <div className="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full blur-3xl" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl" />
        
        {/* Decorative floating hearts */}
        <div className="absolute top-12 right-20 opacity-20 animate-bounce" style={{ animationDuration: '3s' }}>
          <Heart className="w-12 h-12 text-white fill-white" />
        </div>
        
        <div className="absolute bottom-16 left-16 opacity-20 animate-bounce" style={{ animationDuration: '4s', animationDelay: '1s' }}>
          <Sparkles className="w-10 h-10 text-white" />
        </div>

        {/* Decorative organic shapes with softer colors */}
        <div className="absolute top-8 right-8 w-32 h-32 opacity-20">
          <svg viewBox="0 0 100 100" className="w-full h-full">
            <circle cx="50" cy="50" r="40" fill="white" />
            <circle cx="50" cy="50" r="25" fill="#f5f0ed" />
          </svg>
        </div>

        <div className="relative z-10 max-w-5xl mx-auto text-center">
          <div className="inline-flex items-center gap-2 mb-6 px-6 py-3 bg-white/30 backdrop-blur-sm rounded-full border-2 border-white/40">
            <Heart className="w-4 h-4 text-white fill-white" />
            <span className="text-white tracking-wide">Welcome to Our Community</span>
          </div>
          
          <h1 className="text-white mb-4">Together We Make a Difference</h1>
          
          <p className="text-white/95 leading-relaxed mb-6 text-lg">
            Join our vibrant community of changemakers across Tunisia! 🌟
          </p>
          
          <p className="text-white/90 leading-relaxed mb-10 max-w-3xl mx-auto">
            Whether you're passionate about the environment, education, or simply want to help your neighbors, 
            there's a place for you here. Every contribution, big or small, creates ripples of positive change 
            in our communities. Let's build a brighter future together!
          </p>

          {/* Welcoming stats */}
          <div className="flex flex-wrap justify-center gap-6 mb-10">
            <div className="bg-white/20 backdrop-blur-sm rounded-3xl px-6 py-4 border-2 border-white/30">
              <div className="flex items-center gap-2 text-white">
                <Users className="w-5 h-5" />
                <div className="text-left">
                  <div className="text-2xl">500+</div>
                  <div className="text-sm opacity-90">Happy Volunteers</div>
                </div>
              </div>
            </div>
            
            <div className="bg-white/20 backdrop-blur-sm rounded-3xl px-6 py-4 border-2 border-white/30">
              <div className="flex items-center gap-2 text-white">
                <Heart className="w-5 h-5 fill-white" />
                <div className="text-left">
                  <div className="text-2xl">150+</div>
                  <div className="text-sm opacity-90">Active Projects</div>
                </div>
              </div>
            </div>
            
            <div className="bg-white/20 backdrop-blur-sm rounded-3xl px-6 py-4 border-2 border-white/30">
              <div className="flex items-center gap-2 text-white">
                <Sparkles className="w-5 h-5" />
                <div className="text-left">
                  <div className="text-2xl">25+</div>
                  <div className="text-sm opacity-90">Cities Across Tunisia</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </motion.section>
  );
}