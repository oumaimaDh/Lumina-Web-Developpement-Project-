import { Lightbulb, Home, HandHeart, Calendar, Menu, Search, Plus } from "lucide-react";

interface NavbarProps {
  onPostClick: () => void;
  onNavigate?: (page: "home" | "events") => void;
  currentPage?: "home" | "events";
}

export function Navbar({ onPostClick, onNavigate, currentPage = "home" }: NavbarProps) {
  return (
    <nav className="bg-white shadow-md sticky top-0 z-50 border-b-4 border-[#8195b8]">
      <div className="max-w-7xl mx-auto px-6 py-4">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-8">
            <button className="lg:hidden text-[#1e3a5f]">
              <Menu className="w-6 h-6" />
            </button>
            
            <button 
              onClick={() => onNavigate?.("home")}
              className="flex items-center gap-3 text-[#1e3a5f] hover:opacity-80 transition-opacity"
            >
              <div className="bg-[#e8d9de] p-2 rounded-full">
                <Lightbulb className="w-6 h-6" />
              </div>
              <span className="text-2xl">Lumina</span>
            </button>
            
            <ul className="hidden lg:flex items-center gap-8">
              <li>
                <button 
                  onClick={() => onNavigate?.("home")}
                  className={`flex items-center gap-2 transition-colors ${
                    currentPage === "home" 
                      ? "text-[#1e3a5f]" 
                      : "text-[#5b7ba4] hover:text-[#1e3a5f]"
                  }`}
                >
                  <Home className="w-4 h-4" />
                  <span>Home</span>
                </button>
              </li>
              <li>
                <a href="#" className="flex items-center gap-2 text-[#5b7ba4] hover:text-[#1e3a5f] transition-colors">
                  <HandHeart className="w-4 h-4" />
                  <span>Social Department</span>
                </a>
              </li>
              <li>
                <button 
                  onClick={() => onNavigate?.("events")}
                  className={`flex items-center gap-2 transition-colors ${
                    currentPage === "events" 
                      ? "text-[#1e3a5f]" 
                      : "text-[#5b7ba4] hover:text-[#1e3a5f]"
                  }`}
                >
                  <Calendar className="w-4 h-4" />
                  <span>Events</span>
                </button>
              </li>
            </ul>
          </div>
          
          <div className="flex items-center gap-4">
            <button className="text-[#5b7ba4] hover:text-[#1e3a5f] transition-colors">
              <Search className="w-5 h-5" />
            </button>
            <button 
              onClick={onPostClick}
              className="bg-[#1e3a5f] text-white px-6 py-2 rounded-full hover:bg-[#5b7ba4] transition-all flex items-center gap-2 shadow-lg"
            >
              <Plus className="w-4 h-4" />
              <span className="hidden sm:inline">Post</span>
            </button>
          </div>
        </div>
      </div>
    </nav>
  );
}