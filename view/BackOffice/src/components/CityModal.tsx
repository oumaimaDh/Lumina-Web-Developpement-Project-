import { motion, AnimatePresence } from 'motion/react';
import { City } from '../types/city';
import { X, MapPin, Sparkles } from 'lucide-react';
import { Button } from './ui/button';
import { Card } from './ui/card';
import { StarIcon } from './StarIcon';
import { ImageWithFallback } from './figma/ImageWithFallback';

interface CityModalProps {
  city: City | null;
  onClose: () => void;
}

export function CityModal({ city, onClose }: CityModalProps) {
  return (
    <AnimatePresence>
      {city && (
        <>
          {/* Backdrop */}
          <motion.div
            className="fixed inset-0 bg-navy/80 backdrop-blur-sm z-40"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onClose}
          />

          {/* Modal */}
          <motion.div
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          >
            <motion.div
              className="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto"
              initial={{ scale: 0.8, y: 50 }}
              animate={{ scale: 1, y: 0 }}
              exit={{ scale: 0.8, y: 50 }}
              transition={{ type: "spring", damping: 25, stiffness: 300 }}
              onClick={(e) => e.stopPropagation()}
            >
              <Card className="bg-light-blue border-2 border-cream relative overflow-hidden">
                {/* Decorative stars */}
                <div className="absolute top-4 right-20 text-cream opacity-40">
                  <StarIcon className="w-6 h-6" />
                </div>
                <div className="absolute bottom-10 left-10 text-cream opacity-30">
                  <StarIcon className="w-8 h-8" />
                </div>
                <div className="absolute top-1/3 right-10 text-cream opacity-25">
                  <StarIcon className="w-5 h-5" />
                </div>

                {/* Close button */}
                <Button
                  variant="ghost"
                  size="icon"
                  className="absolute top-4 right-4 z-10 text-navy hover:bg-cream/20"
                  onClick={onClose}
                >
                  <X className="h-6 w-6" />
                </Button>

                <div className="p-8">
                  {/* Header */}
                  <motion.div
                    className="mb-6"
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.2 }}
                  >
                    <div className="flex items-center gap-3 mb-2">
                      <motion.div
                        animate={{ rotate: [0, 15, -15, 0] }}
                        transition={{ duration: 2, repeat: Infinity }}
                      >
                        <MapPin className="h-8 w-8 text-navy" />
                      </motion.div>
                      <div>
                        <h2 className="text-4xl text-navy">{city.name}</h2>
                        <p className="text-2xl text-navy/70">{city.nameAr}</p>
                      </div>
                    </div>
                  </motion.div>

                  {/* Description */}
                  <motion.div
                    className="mb-6"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.3 }}
                  >
                    <p className="text-navy/90 text-lg leading-relaxed">
                      {city.description}
                    </p>
                  </motion.div>

                  {/* Highlights */}
                  <motion.div
                    className="mb-6"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.4 }}
                  >
                    <div className="flex items-center gap-2 mb-4">
                      <Sparkles className="h-5 w-5 text-cream" />
                      <h3 className="text-xl text-navy">Highlights</h3>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                      {city.highlights.map((highlight, index) => (
                        <motion.div
                          key={index}
                          className="flex items-center gap-3 bg-cream/30 p-3 rounded-lg"
                          initial={{ opacity: 0, x: -20 }}
                          animate={{ opacity: 1, x: 0 }}
                          transition={{ delay: 0.5 + index * 0.1 }}
                        >
                          <StarIcon className="w-5 h-5 text-navy flex-shrink-0" />
                          <span className="text-navy">{highlight}</span>
                        </motion.div>
                      ))}
                    </div>
                  </motion.div>

                  {/* Image Gallery Placeholder */}
                  <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ delay: 0.6 }}
                  >
                    <h3 className="text-xl text-navy mb-4">Gallery</h3>
                    <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                      {[1, 2, 3].map((i) => (
                        <motion.div
                          key={i}
                          className="aspect-square bg-navy/10 rounded-lg overflow-hidden"
                          whileHover={{ scale: 1.05 }}
                          transition={{ type: "spring", stiffness: 300 }}
                        >
                          <div className="w-full h-full flex items-center justify-center text-navy/30">
                            <Sparkles className="h-12 w-12" />
                          </div>
                        </motion.div>
                      ))}
                    </div>
                  </motion.div>

                  {/* CTA */}
                  <motion.div
                    className="mt-8 flex justify-center"
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.7 }}
                  >
                    <Button
                      size="lg"
                      className="bg-navy hover:bg-navy/90 text-cream"
                    >
                      Explore More
                    </Button>
                  </motion.div>
                </div>
              </Card>
            </motion.div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
}
