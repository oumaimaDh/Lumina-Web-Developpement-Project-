import { Lightbulb, Facebook, Instagram, Twitter, Heart } from "lucide-react";

export function Footer() {
  return (
    <footer className="mt-16 relative">
      {/* Main Footer */}
      <div className="bg-gradient-to-br from-[#1e3a5f] to-[#5b7ba4] rounded-t-[3rem] px-8 md:px-12 pt-12 pb-6 relative overflow-hidden">
        {/* Decorative elements */}
        <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl" />

        <div className="relative z-10">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {/* Branding */}
            <div>
              <div className="flex items-center gap-2 text-white mb-4">
                <div className="bg-white p-2 rounded-full">
                  <Lightbulb className="w-5 h-5 text-[#1e3a5f]" />
                </div>
                <span className="text-xl">Lumina</span>
              </div>
              <p className="text-white/80 text-sm mb-4">
                Building stronger communities, one smile at a time 😊
              </p>
              <p className="text-white/60 text-xs">
                Made with ❤️ in Tunisia
              </p>
            </div>

            {/* Quick Links */}
            <div>
              <h4 className="text-white mb-4">Explore</h4>
              <ul className="space-y-2">
                <li><a href="#" className="text-white/80 hover:text-white text-sm transition-colors flex items-center gap-2">
                  <span>→</span> About Our Community
                </a></li>
                <li><a href="#" className="text-white/80 hover:text-white text-sm transition-colors flex items-center gap-2">
                  <span>→</span> Browse Initiatives
                </a></li>
                <li><a href="#" className="text-white/80 hover:text-white text-sm transition-colors flex items-center gap-2">
                  <span>→</span> Become a Volunteer
                </a></li>
                <li><a href="#" className="text-white/80 hover:text-white text-sm transition-colors flex items-center gap-2">
                  <span>→</span> Success Stories
                </a></li>
              </ul>
            </div>

            {/* Social */}
            <div>
              <h4 className="text-white mb-4">Join Our Community 🌟</h4>
              <p className="text-white/80 text-sm mb-4">
                Follow us for daily inspiration and updates!
              </p>
              <div className="flex gap-3">
                <a href="#" className="bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all hover:scale-110">
                  <Facebook className="w-5 h-5 text-white" />
                </a>
                <a href="#" className="bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all hover:scale-110">
                  <Instagram className="w-5 h-5 text-white" />
                </a>
                <a href="#" className="bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all hover:scale-110">
                  <Twitter className="w-5 h-5 text-white" />
                </a>
              </div>
            </div>
          </div>

          <div className="border-t border-white/20 pt-6 text-center">
            <p className="text-white/60 text-sm flex items-center justify-center gap-2">
              <Heart className="w-4 h-4 fill-white/60" />
              <span>© 2025 Lumina • Together we're building a brighter Tunisia</span>
              <Heart className="w-4 h-4 fill-white/60" />
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
}