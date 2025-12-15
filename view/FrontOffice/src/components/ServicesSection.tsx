import { motion } from "motion/react";
import { Leaf, BookOpen, Users, Heart } from "lucide-react";

export function ServicesSection() {
  const categories = [
    {
      title: "🌱 Environment",
      icon: Leaf,
      emoji: "🌿",
      description: "Help keep Tunisia beautiful!",
      items: ["Beach Cleanups", "Tree Planting", "Recycling Drives", "Conservation Projects"]
    },
    {
      title: "📚 Education",
      icon: BookOpen,
      emoji: "✏️",
      description: "Share knowledge, inspire minds",
      items: ["Tutoring Programs", "Street Libraries", "Workshops", "Youth Mentoring"]
    },
    {
      title: "👥 Community",
      icon: Users,
      emoji: "🎉",
      description: "Bring people together",
      items: ["Neighborhood Events", "Cultural Festivals", "Sports Activities", "Social Gatherings"]
    },
    {
      title: "💝 Support",
      icon: Heart,
      emoji: "🤝",
      description: "Lend a helping hand",
      items: ["Food Distribution", "Elderly Care", "Health Campaigns", "Crisis Response"]
    }
  ];

  return (
    <section className="mt-16">
      <div className="text-center mb-12">
        <div className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-8 py-3 rounded-full mb-4 shadow-lg">
          <h2 className="text-white">How Can You Help? 🌟</h2>
        </div>
        <p className="text-[#5b7ba4] max-w-2xl mx-auto mt-4">
          Pick what speaks to your heart – there's no wrong choice! Every contribution makes our community stronger.
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {categories.map((category, idx) => (
          <motion.div
            key={category.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: idx * 0.1 }}
            whileHover={{ y: -5 }}
            className="relative group"
          >
            {/* Organic blob background */}
            <div className="bg-gradient-to-br from-white to-[#f5f0ed] rounded-[2.5rem] p-8 border-2 border-[#8195b8]/20 hover:border-[#8195b8] transition-all duration-300 hover:shadow-2xl">
              <div className="flex items-start gap-4 mb-6">
                <div className="bg-gradient-to-br from-[#6b7da8] to-[#8195b8] p-4 rounded-2xl group-hover:scale-110 transition-transform duration-300 shadow-lg">
                  <category.icon className="w-7 h-7 text-white" />
                </div>
                <div className="flex-1">
                  <h3 className="text-[#1e3a5f] mb-1">{category.title}</h3>
                  <p className="text-[#8195b8] text-sm">{category.description}</p>
                </div>
                <div className="text-3xl">{category.emoji}</div>
              </div>
              
              <ul className="space-y-3">
                {category.items.map((item, i) => (
                  <li key={i} className="flex items-center gap-3 text-[#5b7ba4] bg-white/60 rounded-2xl p-3 hover:bg-white transition-colors">
                    <div className="w-2 h-2 rounded-full bg-gradient-to-r from-[#6b7da8] to-[#8195b8]" />
                    <span className="text-sm">{item}</span>
                  </li>
                ))}
              </ul>

              <button className="mt-6 w-full bg-gradient-to-r from-[#8195b8]/20 to-[#6b7da8]/20 hover:from-[#6b7da8] hover:to-[#8195b8] text-[#1e3a5f] hover:text-white py-3 rounded-2xl transition-all duration-300">
                Explore Opportunities
              </button>

              {/* Decorative element */}
              <div className="absolute bottom-6 right-6 w-20 h-20 opacity-5 group-hover:opacity-10 transition-opacity">
                <category.icon className="w-full h-full text-[#1e3a5f]" />
              </div>
            </div>
          </motion.div>
        ))}
      </div>

      {/* Friendly CTA */}
      <div className="mt-12 text-center bg-gradient-to-br from-[#e8d9de]/40 to-[#f5f0ed] rounded-3xl p-8">
        <h3 className="text-[#1e3a5f] mb-3">Still Not Sure Where to Start? 🤔</h3>
        <p className="text-[#5b7ba4] mb-6">
          No worries! Reach out to us and we'll help you find the perfect match for your interests and schedule. We're here to help!
        </p>
        <button className="bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-8 py-3 rounded-full hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
          Chat With Us! 💬
        </button>
      </div>
    </section>
  );
}