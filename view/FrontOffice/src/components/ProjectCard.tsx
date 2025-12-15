import { motion } from "motion/react";
import { Calendar, MapPin, Users, Heart } from "lucide-react";
import { ImageWithFallback } from "./figma/ImageWithFallback";

interface ProjectCardProps {
  image: string;
  title: string;
  description: string;
}

export function ProjectCard({ image, title, description }: ProjectCardProps) {
  return (
    <motion.div
      whileHover={{ y: -5 }}
      transition={{ duration: 0.3 }}
      className="group relative bg-white rounded-[2rem] overflow-hidden shadow-md border-2 border-[#8195b8]/20 hover:border-[#8195b8] hover:shadow-2xl transition-all duration-300"
    >
      {/* Image with organic overlay */}
      <div className="relative h-56 overflow-hidden">
        <ImageWithFallback
          src={image}
          alt={title}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        />
        
        {/* Friendly gradient overlay */}
        <div className="absolute inset-0 bg-gradient-to-t from-[#6b7da8]/90 via-[#6b7da8]/30 to-transparent" />
        
        {/* Floating friendly tag */}
        <div className="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full text-xs text-[#1e3a5f] flex items-center gap-1 shadow-lg">
          <Heart className="w-3 h-3 fill-[#5b7ba4] text-[#5b7ba4]" />
          <span>Join Us!</span>
        </div>
      </div>

      <div className="p-6">
        <h3 className="text-[#1e3a5f] mb-2">{title}</h3>
        <p className="text-[#5b7ba4] text-sm mb-4 leading-relaxed">{description}</p>
        
        {/* Meta information with friendly icons */}
        <div className="flex flex-wrap gap-3 text-xs text-[#8195b8] mb-4">
          <div className="flex items-center gap-1 bg-[#f5f0ed] px-3 py-1.5 rounded-full">
            <Calendar className="w-3 h-3" />
            <span>Nov 20</span>
          </div>
          <div className="flex items-center gap-1 bg-[#f5f0ed] px-3 py-1.5 rounded-full">
            <MapPin className="w-3 h-3" />
            <span>Tunis</span>
          </div>
          <div className="flex items-center gap-1 bg-[#f5f0ed] px-3 py-1.5 rounded-full">
            <Users className="w-3 h-3" />
            <span>25 friends</span>
          </div>
        </div>

        <button className="w-full bg-gradient-to-r from-[#6b7da8] to-[#8195b8] hover:from-[#5b7ba4] hover:to-[#6b7da8] text-white py-3 rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
          Count Me In! ✨
        </button>
      </div>

      {/* Decorative blob */}
      <div className="absolute -bottom-8 -right-8 w-24 h-24 bg-[#8195b8]/10 rounded-full blur-xl group-hover:bg-[#8195b8]/20 transition-colors" />
    </motion.div>
  );
}