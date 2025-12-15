import { motion } from "motion/react";
import { Star, Heart } from "lucide-react";

export function TestimonialsSection() {
  const testimonials = [
    {
      id: 1,
      name: "Amira Ben Salem",
      role: "Environmental Activist",
      avatar: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop",
      text: "Lumina helped me connect with amazing volunteers for our coastal cleanup initiative. Together we've cleaned over 5km of beaches! The community here is so supportive and friendly! 🌊",
      rating: 5,
      emoji: "🌟"
    },
    {
      id: 2,
      name: "Karim Hosni",
      role: "Education Coordinator",
      avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop",
      text: "The platform made it easy to organize our street library project. We now have 12 active reading stations across the city. Every day I see smiling faces discovering new books! 📚",
      rating: 5,
      emoji: "✨"
    }
  ];

  return (
    <section className="mt-16 relative">
      {/* Background blob */}
      <div className="absolute inset-0 -z-10">
        <svg viewBox="0 0 1200 400" className="w-full h-full" preserveAspectRatio="none">
          <path 
            d="M 0 50 Q 600 0 1200 50 L 1200 350 Q 600 400 0 350 Z" 
            fill="#6b7da8"
            opacity="0.05"
          />
        </svg>
      </div>

      <div className="relative bg-gradient-to-br from-[#6b7da8] via-[#8195b8] to-[#a8b5c9] rounded-[3rem] p-12 overflow-hidden">
        {/* Decorative blobs */}
        <div className="absolute top-0 left-0 w-64 h-64 bg-white/20 rounded-full blur-3xl" />
        <div className="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl" />

        {/* Floating hearts */}
        <div className="absolute top-8 right-20 opacity-20 animate-pulse">
          <Heart className="w-16 h-16 text-white fill-white" />
        </div>

        <div className="relative z-10">
          <div className="text-center mb-10">
            <div className="inline-flex items-center gap-2 bg-white/30 backdrop-blur-sm px-6 py-3 rounded-full mb-4">
              <Heart className="w-5 h-5 text-white fill-white" />
              <span className="text-white">Stories From Our Family</span>
            </div>
            <h2 className="text-white mb-2">Real Stories, Real Impact 💙</h2>
            <p className="text-white/90 max-w-2xl mx-auto">
              Hear from wonderful people who've found their place in our community. You could be next!
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            {testimonials.map((testimonial, idx) => (
              <motion.div
                key={testimonial.id}
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.5, delay: idx * 0.2 }}
                whileHover={{ y: -5 }}
                className="bg-white/95 backdrop-blur-sm rounded-3xl p-8 relative"
              >
                {/* Emoji decoration */}
                <div className="absolute top-6 right-6 text-4xl opacity-20">
                  {testimonial.emoji}
                </div>

                <div className="flex flex-col items-center text-center relative z-10">
                  <div className="relative mb-4">
                    <img
                      src={testimonial.avatar}
                      alt={testimonial.name}
                      className="w-20 h-20 rounded-full object-cover ring-4 ring-[#8195b8]/30"
                    />
                    <div className="absolute -bottom-2 -right-2 bg-gradient-to-br from-[#6b7da8] to-[#8195b8] p-2 rounded-full shadow-lg">
                      <Heart className="w-4 h-4 text-white fill-white" />
                    </div>
                  </div>
                  
                  <h4 className="text-[#1e3a5f] mb-1">{testimonial.name}</h4>
                  <p className="text-[#8195b8] text-sm mb-4">{testimonial.role}</p>
                  
                  {/* Rating */}
                  <div className="flex gap-1 mb-4">
                    {[...Array(testimonial.rating)].map((_, i) => (
                      <Star key={i} className="w-4 h-4 fill-[#8195b8] text-[#8195b8]" />
                    ))}
                  </div>
                  
                  <p className="text-[#5b7ba4] leading-relaxed">
                    "{testimonial.text}"
                  </p>
                </div>
              </motion.div>
            ))}
          </div>

          {/* Friendly CTA */}
          <div className="text-center mt-10">
            <p className="text-white mb-4">Want to share your story too? Join us today!</p>
            <button className="bg-white text-[#1e3a5f] px-8 py-3 rounded-full hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
              Become Part of Our Story 🌟
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}