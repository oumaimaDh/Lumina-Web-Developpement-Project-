// Map utilities for Tunisia map functionality
// Geocoding function to convert address to coordinates
// Note: Nominatim has a rate limit of 1 request per second
let lastGeocodeTime = 0;
async function geocodeAddress(address, retryCount = 0) {
    try {
        // Rate limiting: wait at least 1 second between requests
        const now = Date.now();
        const timeSinceLastRequest = now - lastGeocodeTime;
        if (timeSinceLastRequest < 1000) {
            await new Promise(resolve => setTimeout(resolve, 1000 - timeSinceLastRequest));
        }
        lastGeocodeTime = Date.now();
        
        // Clean address - remove "Tunisie" if already present since we'll add it
        let cleanAddress = address.trim();
        if (cleanAddress.toLowerCase().endsWith(', tunisie') || cleanAddress.toLowerCase().endsWith(', tunisia')) {
            cleanAddress = cleanAddress.replace(/,\s*(tunisie|tunisia)$/i, '').trim();
        }
        
        // Extract parts for better variations
        const parts = cleanAddress.split(',').map(p => p.trim()).filter(p => p);
        
        // Try different address formats
        const addressVariations = [
            cleanAddress + ', Tunisia',
            cleanAddress,
            // Try without postal code
            parts.filter(p => !/^\d{4}$/.test(p)).join(', ') + ', Tunisia',
            // Try with just neighborhood and city
            parts.slice(0, 2).join(', ') + ', Tunisia',
            // Try with just first part (neighborhood)
            parts[0] + ', Tunisia',
            // Try with delegation and governorate
            parts.filter(p => p.toLowerCase().includes('délégation') || p.toLowerCase().includes('gouvernorat')).join(', ') + ', Tunisia',
            // Try with just city name (last meaningful part before postal code)
            parts.filter(p => !/^\d{4}$/.test(p)).slice(-1)[0] + ', Tunisia',
            // Try with just "Soukra" or similar city names
            parts.find(p => p.toLowerCase().includes('soukra') || p.toLowerCase().includes('ariana') || p.toLowerCase().includes('gafsa')) + ', Tunisia'
        ].filter(v => v && v !== ', Tunisia'); // Remove empty variations
        
        // Try each variation
        for (let i = 0; i < addressVariations.length; i++) {
            const searchQuery = addressVariations[i];
            
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}&limit=1&countrycodes=tn`, {
                    headers: {
                        'User-Agent': 'SocialCaseApp/1.0'
                    }
                });
                
                if (!response.ok) {
                    console.warn(`Geocoding request failed with status: ${response.status}`);
                    continue;
                }
                
                const data = await response.json();
                if (data && data.length > 0) {
                    const result = {
                        lat: parseFloat(data[0].lat),
                        lng: parseFloat(data[0].lon),
                        display_name: data[0].display_name
                    };
                    // Validate coordinates are in Tunisia
                    if (!isNaN(result.lat) && !isNaN(result.lng) && result.lat >= 30 && result.lat <= 38 && result.lng >= 7 && result.lng <= 12) {
                        if (i > 0) {
                            console.log(`Geocoded with variation ${i + 1}: "${searchQuery}"`);
                        }
                        return result;
                    }
                }
                
                // Small delay between variations
                if (i < addressVariations.length - 1) {
                    await new Promise(resolve => setTimeout(resolve, 500));
                }
            } catch (fetchError) {
                console.warn(`Geocoding variation ${i + 1} failed:`, fetchError);
                continue;
            }
        }
        
        return null;
    } catch (error) {
        console.error('Geocoding error:', error);
        // Retry once if network error
        if (retryCount < 1 && (error.message.includes('fetch') || error.message.includes('network'))) {
            console.log('Retrying geocoding...');
            await new Promise(resolve => setTimeout(resolve, 2000));
            return geocodeAddress(address, retryCount + 1);
        }
        return null;
    }
}

// Reverse geocoding to convert coordinates to address
// Note: Nominatim has a rate limit of 1 request per second
let lastReverseGeocodeTime = 0;
async function reverseGeocode(lat, lng) {
    try {
        // Rate limiting: wait at least 1 second between requests
        const now = Date.now();
        const timeSinceLastRequest = now - lastReverseGeocodeTime;
        if (timeSinceLastRequest < 1000) {
            await new Promise(resolve => setTimeout(resolve, 1000 - timeSinceLastRequest));
        }
        lastReverseGeocodeTime = Date.now();
        
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
            headers: {
                'User-Agent': 'SocialCaseApp/1.0'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        if (data && data.address) {
            // Build address in the format: "Cite Ech-Chabab, Délégation Gafsa Sud, Gouvernorat Gafsa, Tunisie"
            const addr = data.address;
            const parts = [];
            
            // 1. Add neighborhood/village/suburb/city district (first part)
            if (addr.neighbourhood) {
                parts.push(addr.neighbourhood);
            } else if (addr.village) {
                parts.push(addr.village);
            } else if (addr.suburb) {
                parts.push(addr.suburb);
            } else if (addr.city_district) {
                parts.push(addr.city_district);
            } else if (addr.quarter) {
                parts.push(addr.quarter);
            }
            
            // 2. Add delegation (Délégation) - Tunisian administrative division (county)
            if (addr.county) {
                // Check if it already contains "Délégation", if not add it
                let delegation = addr.county;
                if (!delegation.toLowerCase().includes('délégation') && !delegation.toLowerCase().includes('delegation')) {
                    delegation = 'Délégation ' + delegation;
                }
                parts.push(delegation);
            }
            
            // 3. Add governorate (Gouvernorat) - Tunisian administrative division (state)
            if (addr.state) {
                // Check if it already contains "Gouvernorat", if not add it
                let governorate = addr.state;
                if (!governorate.toLowerCase().includes('gouvernorat') && !governorate.toLowerCase().includes('governorate')) {
                    governorate = 'Gouvernorat ' + governorate;
                }
                parts.push(governorate);
            }
            
            // 4. Always end with "Tunisie"
            if (parts.length > 0) {
                return parts.join(', ') + ', Tunisie';
            } else if (data.display_name) {
                // Fallback: parse display_name and format it
                let displayName = data.display_name;
                // Remove coordinate patterns and country if present
                displayName = displayName.replace(/\s*[-|]\s*\d+\.?\d*\s*,\s*\d+\.?\d*\s*$/, '').trim();
                displayName = displayName.replace(/,\s*Tunisia$/i, '').trim();
                // Try to extract parts from display_name
                const nameParts = displayName.split(',').map(p => p.trim()).filter(p => p);
                if (nameParts.length > 0) {
                    return nameParts.join(', ') + ', Tunisie';
                }
                return displayName + ', Tunisie';
            } else {
                // Last fallback
                return 'Location, Tunisie';
            }
        }
        
        // If no address data, return a generic location name instead of coordinates
        return 'Location sélectionnée, Tunisie';
    } catch (error) {
        console.error('Reverse geocoding error:', error);
        // Return a user-friendly message instead of coordinates
        // Try to determine approximate location based on coordinates
        if (lat >= 36 && lat <= 37 && lng >= 10 && lng <= 11) {
            return 'Région de Tunis, Gouvernorat Tunis, Tunisie';
        } else if (lat >= 34 && lat <= 35 && lng >= 10 && lng <= 11) {
            return 'Région de Sfax, Gouvernorat Sfax, Tunisie';
        } else if (lat >= 35 && lat <= 36 && lng >= 10 && lng <= 11) {
            return 'Région de Sousse, Gouvernorat Sousse, Tunisie';
        } else {
            return 'Location sélectionnée, Tunisie';
        }
    }
}

// Calculate distance between two coordinates using Haversine formula
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Earth's radius in kilometers
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = 
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLng / 2) * Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // Distance in kilometers
}

// Initialize Tunisia map
function initTunisiaMap(mapId, centerLat = 33.8869, centerLng = 10.1775, zoom = 7) {
    const map = L.map(mapId).setView([centerLat, centerLng], zoom);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    return map;
}

// Add marker to map
function addMarker(map, lat, lng, title, popupContent) {
    const marker = L.marker([lat, lng]).addTo(map);
    if (popupContent) {
        marker.bindPopup(popupContent);
    } else if (title) {
        marker.bindPopup(title);
    }
    return marker;
}

// Parse coordinates from location string (handles formats like "lat, lng" or "lat|lng")
function parseCoordinates(location) {
    if (!location || typeof location !== 'string') return null;
    
    // Clean the location string
    location = location.trim();
    
    // Try to match coordinate patterns
    // Pattern 1: "lat, lng" or "lat,lng" or "lat|lat2, lng|lng2"
    // Also handle cases like "35.416514, 9.820311|35.41651404740733,9.820310924717678"
    let match = location.match(/(\d+\.?\d*)\s*[,|]\s*(\d+\.?\d*)/);
    if (match) {
        const lat = parseFloat(match[1]);
        const lng = parseFloat(match[2]);
        // Validate coordinates (Tunisia is roughly 30-37°N, 7-12°E)
        // But be more lenient - allow slightly outside range for edge cases
        if (!isNaN(lat) && !isNaN(lng) && lat >= 25 && lat <= 40 && lng >= 5 && lng <= 15) {
            return { lat: lat, lng: lng, display_name: location };
        }
    }
    
    // Try pattern with multiple coordinates (take first pair)
    // Pattern: "lat1, lng1|lat2, lng2"
    let multiMatch = location.match(/(\d+\.?\d*)\s*,\s*(\d+\.?\d*)/);
    if (multiMatch) {
        const lat = parseFloat(multiMatch[1]);
        const lng = parseFloat(multiMatch[2]);
        if (!isNaN(lat) && !isNaN(lng) && lat >= 25 && lat <= 40 && lng >= 5 && lng <= 15) {
            return { lat: lat, lng: lng, display_name: location };
        }
    }
    
    return null;
}

// Get coordinates from location (either parse from string or geocode)
async function getLocationCoordinates(location) {
    if (!location || location.trim() === '') {
        console.warn('Empty location provided');
        return null;
    }
    
    // First, try to parse as coordinates
    const coords = parseCoordinates(location);
    if (coords) {
        console.log('✓ Parsed coordinates from:', location, '->', coords);
        return coords;
    }
    
    // If not coordinates, try geocoding with multiple strategies
    console.log('Attempting to geocode:', location);
    const geocoded = await geocodeAddress(location);
    if (geocoded) {
        console.log('✓ Geocoded successfully:', location, '->', geocoded);
        return geocoded;
    }
    
    // If geocoding failed, try additional fallback strategies
    console.warn('✗ Initial geocoding failed for:', location);
    
    // Strategy 1: Try with just neighborhood name
    const parts = location.split(',').map(p => p.trim());
    if (parts.length > 0) {
        const neighborhood = parts[0];
        if (neighborhood && neighborhood.length > 3) {
            console.log('Trying with neighborhood only:', neighborhood);
            const retry1 = await geocodeAddress(neighborhood + ', Tunisia');
            if (retry1) {
                console.log('✓ Geocoded with neighborhood:', neighborhood);
                return retry1;
            }
        }
    }
    
    // Strategy 2: Try with city name (look for "Soukra", "Ariana", "Gafsa", etc.)
    const cityKeywords = ['Soukra', 'Ariana', 'Gafsa', 'Tunis', 'Sfax', 'Sousse'];
    for (const keyword of cityKeywords) {
        if (location.toLowerCase().includes(keyword.toLowerCase())) {
            console.log('Trying with city keyword:', keyword);
            const retry2 = await geocodeAddress(keyword + ', Tunisia');
            if (retry2) {
                console.log('✓ Geocoded with city keyword:', keyword);
                return retry2;
            }
        }
    }
    
    console.warn('✗ All geocoding attempts failed for:', location);
    return null;
}

// Create a custom light yellow/white icon for association markers
function createAssociationIcon() {
    return L.divIcon({
        className: 'custom-association-marker',
        html: '<div style="background-color: #FFEB3B; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #FFFFFF; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"><div style="width: 12px; height: 12px; background-color: #FFFFFF; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);"></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });
}

// Add association marker to map with custom icon
function addAssociationMarker(map, lat, lng, title, popupContent) {
    const customIcon = createAssociationIcon();
    const marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
    if (popupContent) {
        marker.bindPopup(popupContent);
    } else if (title) {
        marker.bindPopup(title);
    }
    return marker;
}

// Batch geocode addresses
async function geocodeAssociations(associations) {
    const geocoded = [];
    for (const assoc of associations) {
        const coords = await getLocationCoordinates(assoc.location);
        if (coords) {
            geocoded.push({
                ...assoc,
                lat: coords.lat,
                lng: coords.lng
            });
        }
    }
    return geocoded;
}

