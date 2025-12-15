import { motion } from "motion/react";
import { useState } from "react";
import { Navbar } from "./components/Navbar";
import { Hero } from "./components/Hero";
import { AboutSection } from "./components/AboutSection";
import { ServicesSection } from "./components/ServicesSection";
import { ProjectCard } from "./components/ProjectCard";
import { FeedPost } from "./components/FeedPost";
import { TestimonialsSection } from "./components/TestimonialsSection";
import { FAQSection } from "./components/FAQSection";
import { Footer } from "./components/Footer";
import { PostModal } from "./components/PostModal";
import { EventsPage } from "./components/EventsPage";

export default function App() {
  const [isPostModalOpen, setIsPostModalOpen] = useState(false);
  const [currentPage, setCurrentPage] = useState<"home" | "events">("home");
  const [discussions, setDiscussions] = useState([
    {
      id: 1,
      avatar: "https://images.unsplash.com/photo-1639149888905-fb39731f2e6c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwZXJzb24lMjBhdmF0YXIlMjBwb3J0cmFpdHxlbnwxfHx8fDE3NjMzMTg4ODd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      name: "Ons Marzouki",
      message: "We are planning a new awareness event in Nabeul! Suggestions welcome. Would love to have you join us!",
      timeAgo: "2 hours ago"
    },
    {
      id: 2,
      avatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwyfHxwZXJzb24lMjBhdmF0YXIlMjBwb3J0cmFpdHxlbnwxfHx8fDE3NjMzMTg4ODd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      name: "Rami Toumi",
      message: "Who wants to volunteer for our community center this Saturday? All skill levels welcome! 🙌",
      timeAgo: "5 hours ago"
    },
    {
      id: 3,
      avatar: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwzfHxwZXJzb24lMjBhdmF0YXIlMjBwb3J0cmFpdHxlbnwxfHx8fDE3NjMzMTg4ODd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      name: "Leila Ben Ahmed",
      message: "Just finished organizing a book drive for local schools. We collected over 500 books! Thank you to everyone who contributed! 📚💙",
      timeAgo: "1 day ago"
    },
    {
      id: 4,
      avatar: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHw0fHxwZXJzb24lMjBhdmF0YXIlMjBwb3J0cmFpdHxlbnwxfHx8fDE3NjMzMTg4ODd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      name: "Karim Mansour",
      message: "Looking for volunteers to help with our beach cleanup next weekend in La Marsa. Bring your friends and family! 🌊",
      timeAgo: "1 day ago"
    }
  ]);

  const handleNewPost = (content: string) => {
    const newPost = {
      id: discussions.length + 1,
      avatar: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHw1fHxwZXJzb24lMjBhdmF0YXIlMjBwb3J0cmFpdHxlbnwxfHx8fDE3NjMzMTg4ODd8MA&ixlib=rb-4.1.0&q=80&w=1080",
      name: "You",
      message: content,
      timeAgo: "Just now"
    };
    setDiscussions([newPost, ...discussions]);
  };

  const projects = [
    {
      id: 1,
      image: "https://images.unsplash.com/photo-1758599668299-beebedfabf7b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb21tdW5pdHklMjBjbGVhbnVwJTIwdm9sdW50ZWVyc3xlbnwxfHx8fDE3NjMzMjIyNDF8MA&ixlib=rb-4.1.0&q=80&w=1080",
      title: "Neighborhood Cleanup",
      description: "Local residents joined forces to clean the shoreline in Sousse."
    },
    {
      id: 2,
      image: "https://images.unsplash.com/photo-1668934805599-91f032937f14?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzdHJlZXQlMjBsaWJyYXJ5JTIwYm9va3N8ZW58MXx8fHwxNzYzMzIyMjQyfDA&ixlib=rb-4.1.0&q=80&w=1080",
      title: "Street Library Initiative",
      description: "Volunteers set up open book-sharing stands in public squares."
    },
    {
      id: 3,
      image: "https://images.unsplash.com/photo-1654343532574-53e699fe0cd8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjaGlsZHJlbiUyMGFjdGl2aXRpZXMlMjBvdXRkb29yfGVufDF8fHx8MTc2MzMyMjI0Mnww&ixlib=rb-4.1.0&q=80&w=1080",
      title: "Kids Activity Day",
      description: "A full-day program of games and learning activities for children."
    }
  ];

  return (
    <div className="min-h-screen bg-white">
      <Navbar onPostClick={() => setIsPostModalOpen(true)} onNavigate={setCurrentPage} currentPage={currentPage} />
      
      {currentPage === "home" ? (
        <>
          <div className="max-w-7xl mx-auto px-6 pb-12">
            <Hero />
            
            <AboutSection />
            
            <ServicesSection />

            {/* Featured Projects Section */}
            <motion.section
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.4 }}
              className="mt-16"
            >
              <div className="text-center mb-10">
                <div className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-6 py-2 rounded-full mb-3 shadow-lg">
                  <span>✨ Happening Now</span>
                </div>
                <h2 className="text-[#1e3a5f] mb-3">Join These Amazing Initiatives!</h2>
                <p className="text-[#5b7ba4] max-w-2xl mx-auto">
                  These projects are actively looking for enthusiastic volunteers like you. Pick one and make a difference today!
                </p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {projects.map((project) => (
                  <ProjectCard
                    key={project.id}
                    image={project.image}
                    title={project.title}
                    description={project.description}
                  />
                ))}
              </div>
            </motion.section>

            {/* Community Feed Section */}
            <motion.section
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8, delay: 0.5 }}
              className="mt-16"
            >
              <div className="text-center mb-10">
                <div className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-6 py-2 rounded-full mb-3 shadow-lg">
                  <span>💬 Community Buzz</span>
                </div>
                <h2 className="text-[#1e3a5f] mb-3">What's Happening in Our Community</h2>
                <p className="text-[#5b7ba4] max-w-2xl mx-auto mb-6">
                  See what your neighbors are up to and join the conversation!
                </p>
                
                {/* Create Post Button */}
                <button
                  onClick={() => setIsPostModalOpen(true)}
                  className="inline-flex items-center gap-2 bg-gradient-to-r from-[#6b7da8] to-[#8195b8] text-white px-8 py-4 rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all"
                >
                  <span className="text-2xl">✏️</span>
                  <span>Share Something with the Community!</span>
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                {discussions.map((discussion) => (
                  <FeedPost
                    key={discussion.id}
                    avatar={discussion.avatar}
                    name={discussion.name}
                    message={discussion.message}
                    timeAgo={discussion.timeAgo}
                  />
                ))}
              </div>
            </motion.section>

            <TestimonialsSection />
            
            <FAQSection />
          </div>

          <Footer />
        </>
      ) : (
        <>
          <EventsPage />
          <Footer />
        </>
      )}
      
      <PostModal isOpen={isPostModalOpen} onClose={() => setIsPostModalOpen(false)} onSubmit={handleNewPost} />
    </div>
  );
}