import { motion } from 'motion/react';
import { City } from '../types/city';
import { StarIcon } from './StarIcon';
import tunisiaMapImage from 'figma:asset/2c0e05636701e283577fb56d56e2021de8d6f407.png';

interface TunisiaMapProps {
  cities: City[];
  onCityClick: (city: City) => void;
  selectedCity?: City | null;
}

export function TunisiaMap({ cities, onCityClick, selectedCity }: TunisiaMapProps) {
  return (
    <div className="relative w-full max-w-4xl mx-auto">
      {/* Map Image Container with enhanced styling */}
      <motion.div
        className="relative rounded-2xl overflow-hidden shadow-2xl"
        initial={{ opacity: 0, scale: 0.9 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 1.5, ease: "easeOut" }}
      >
        {/* Decorative border glow */}
        <div className="absolute inset-0 rounded-2xl border-4 border-cream/30 pointer-events-none z-20" />
        
        {/* Gradient overlay for depth */}
        <div className="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-navy/10 pointer-events-none z-10" />

        {/* Tunisia Map Image */}
        <div className="relative">
          <img
            src={tunisiaMapImage}
            alt="Tunisia Map"
            className="w-full h-auto block"
            style={{ filter: 'brightness(1.05) contrast(1.1) saturate(1.15)' }}
          />
        </div>

        {/* Overlay container for clickable stars */}
        <div className="absolute inset-0 z-10">
          <svg
            viewBox="0 0 100 100"
            className="w-full h-full"
            preserveAspectRatio="xMidYMid meet"
          >
            <defs>
              {/* Glowing filter for stars */}
              <filter id="glow">
                <feGaussianBlur stdDeviation="1.5" result="coloredBlur"/>
                <feMerge>
                  <feMergeNode in="coloredBlur"/>
                  <feMergeNode in="SourceGraphic"/>
                </feMerge>
              </filter>
              
              {/* Shadow filter */}
              <filter id="shadow">
                <feDropShadow dx="0" dy="0" stdDeviation="0.8" floodColor="#1a3a52" floodOpacity="0.8"/>
              </filter>
            </defs>

            {/* Cities with clickable stars */}
            {cities.map((city, index) => (
              <g key={city.id}>
                {/* Pulsing glow ring for selected city */}
                {selectedCity?.id === city.id && (
                  <>
                    <motion.circle
                      cx={city.coordinates.x}
                      cy={city.coordinates.y}
                      r="5"
                      fill="none"
                      stroke="#f5e6d3"
                      strokeWidth="0.5"
                      opacity="0.8"
                      initial={{ scale: 0, opacity: 0 }}
                      animate={{ 
                        scale: [1, 1.5, 1],
                        opacity: [0.8, 0.3, 0.8]
                      }}
                      transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
                    />
                    <motion.circle
                      cx={city.coordinates.x}
                      cy={city.coordinates.y}
                      r="3.5"
                      fill="#f5e6d3"
                      opacity="0.3"
                      initial={{ scale: 0 }}
                      animate={{ scale: [1, 1.3, 1] }}
                      transition={{ duration: 1.5, repeat: Infinity }}
                    />
                  </>
                )}
                
                {/* Clickable star with enhanced styling */}
                <motion.g
                  initial={{ scale: 0, opacity: 0 }}
                  animate={{ scale: 1, opacity: 1 }}
                  transition={{ 
                    delay: 0.5 + index * 0.04,
                    duration: 0.8,
                    type: "spring",
                    stiffness: 300,
                    damping: 15
                  }}
                  whileHover={{ scale: 1.6 }}
                  whileTap={{ scale: 0.8 }}
                  onClick={() => onCityClick(city)}
                  className="cursor-pointer"
                  style={{ transformOrigin: `${city.coordinates.x}px ${city.coordinates.y}px` }}
                  filter="url(#shadow)"
                >
                  {/* Outer glow circle */}
                  <motion.circle
                    cx={city.coordinates.x}
                    cy={city.coordinates.y}
                    r="2"
                    fill="#f5e6d3"
                    opacity="0.4"
                    animate={{
                      scale: [1, 1.5, 1],
                      opacity: [0.4, 0.1, 0.4]
                    }}
                    transition={{
                      duration: 2,
                      repeat: Infinity,
                      delay: index * 0.15,
                      ease: "easeInOut"
                    }}
                  />
                  
                  {/* Star shape - larger and more prominent */}
                  <path
                    d={`M ${city.coordinates.x} ${city.coordinates.y - 3} 
                       L ${city.coordinates.x + 0.9} ${city.coordinates.y - 0.9} 
                       L ${city.coordinates.x + 3} ${city.coordinates.y} 
                       L ${city.coordinates.x + 0.9} ${city.coordinates.y + 0.9} 
                       L ${city.coordinates.x} ${city.coordinates.y + 3} 
                       L ${city.coordinates.x - 0.9} ${city.coordinates.y + 0.9} 
                       L ${city.coordinates.x - 3} ${city.coordinates.y} 
                       L ${city.coordinates.x - 0.9} ${city.coordinates.y - 0.9} Z`}
                    fill={selectedCity?.id === city.id ? "#f5e6d3" : "#ffffff"}
                    stroke={selectedCity?.id === city.id ? "#ffffff" : "#1a3a52"}
                    strokeWidth="0.5"
                    filter="url(#glow)"
                  />
                  
                  {/* Inner sparkle */}
                  <circle
                    cx={city.coordinates.x}
                    cy={city.coordinates.y}
                    r="0.8"
                    fill={selectedCity?.id === city.id ? "#ffffff" : "#f5e6d3"}
                    opacity="0.9"
                  />
                  
                  {/* Animated twinkle effect */}
                  <motion.circle
                    cx={city.coordinates.x}
                    cy={city.coordinates.y}
                    r="1"
                    fill="#ffffff"
                    animate={{
                      opacity: [0, 1, 0],
                      scale: [0.5, 2.5, 0.5]
                    }}
                    transition={{
                      duration: 3,
                      repeat: Infinity,
                      delay: index * 0.2,
                      ease: "easeInOut"
                    }}
                  />
                </motion.g>

                {/* City name label with enhanced styling */}
                <motion.g
                  initial={{ opacity: 0 }}
                  whileHover={{ opacity: 1 }}
                  style={{ pointerEvents: 'none' }}
                  transition={{ duration: 0.2 }}
                >
                  {/* Shadow background */}
                  <rect
                    x={city.coordinates.x - 10}
                    y={city.coordinates.y + 4}
                    width="20"
                    height="6"
                    rx="1.5"
                    fill="#1a3a52"
                    opacity="0.95"
                  />
                  {/* Border accent */}
                  <rect
                    x={city.coordinates.x - 10}
                    y={city.coordinates.y + 4}
                    width="20"
                    height="6"
                    rx="1.5"
                    fill="none"
                    stroke="#f5e6d3"
                    strokeWidth="0.3"
                    opacity="0.6"
                  />
                  {/* City name text */}
                  <text
                    x={city.coordinates.x}
                    y={city.coordinates.y + 8}
                    textAnchor="middle"
                    fill="#f5e6d3"
                    fontSize="2.8"
                    fontWeight="700"
                    style={{ textShadow: '0 0 2px rgba(26, 58, 82, 0.8)' }}
                  >
                    {city.name}
                  </text>
                </motion.g>
              </g>
            ))}
          </svg>
        </div>
      </motion.div>

      {/* Decorative animated stars around map - larger and more prominent */}
      <motion.div
        className="absolute -top-8 -right-8 text-cream drop-shadow-2xl"
        animate={{ 
          rotate: 360,
          scale: [1, 1.3, 1]
        }}
        transition={{ 
          rotate: { duration: 20, repeat: Infinity, ease: "linear" },
          scale: { duration: 2, repeat: Infinity, ease: "easeInOut" }
        }}
      >
        <StarIcon className="w-16 h-16" />
      </motion.div>
      
      <motion.div
        className="absolute -bottom-8 -left-8 text-cream drop-shadow-2xl"
        animate={{ 
          rotate: -360,
          scale: [1, 1.4, 1]
        }}
        transition={{ 
          rotate: { duration: 25, repeat: Infinity, ease: "linear" },
          scale: { duration: 2.5, repeat: Infinity, ease: "easeInOut", delay: 0.5 }
        }}
      >
        <StarIcon className="w-20 h-20" />
      </motion.div>
      
      <motion.div
        className="absolute top-1/4 -left-10 text-cream opacity-80 drop-shadow-xl"
        animate={{ 
          y: [0, -20, 0],
          rotate: [0, 180, 360],
          scale: [1, 1.2, 1]
        }}
        transition={{ duration: 5, repeat: Infinity, ease: "easeInOut" }}
      >
        <StarIcon className="w-12 h-12" />
      </motion.div>
      
      <motion.div
        className="absolute top-2/3 -right-10 text-cream opacity-75 drop-shadow-xl"
        animate={{ 
          y: [0, 20, 0],
          rotate: [0, -180, -360],
          scale: [1, 1.3, 1]
        }}
        transition={{ duration: 5.5, repeat: Infinity, delay: 1.5, ease: "easeInOut" }}
      >
        <StarIcon className="w-12 h-12" />
      </motion.div>

      <motion.div
        className="absolute top-1/2 -right-12 text-cream opacity-70 drop-shadow-xl"
        animate={{ 
          x: [0, 15, 0],
          scale: [1, 1.4, 1],
          rotate: [0, 90, 0]
        }}
        transition={{ duration: 4, repeat: Infinity, delay: 0.8, ease: "easeInOut" }}
      >
        <StarIcon className="w-14 h-14" />
      </motion.div>
      
      <motion.div
        className="absolute top-10 -left-12 text-cream opacity-65 drop-shadow-xl"
        animate={{ 
          x: [0, -15, 0],
          y: [0, 10, 0],
          rotate: [0, -90, 0]
        }}
        transition={{ duration: 4.5, repeat: Infinity, delay: 2, ease: "easeInOut" }}
      >
        <StarIcon className="w-11 h-11" />
      </motion.div>

      <motion.div
        className="absolute bottom-20 -right-14 text-cream opacity-60 drop-shadow-lg"
        animate={{ 
          scale: [1, 1.5, 1],
          rotate: 360
        }}
        transition={{ 
          scale: { duration: 3, repeat: Infinity, ease: "easeInOut" },
          rotate: { duration: 15, repeat: Infinity, ease: "linear" }
        }}
      >
        <StarIcon className="w-10 h-10" />
      </motion.div>
    </div>
  );
}
